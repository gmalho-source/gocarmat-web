<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\Redirect;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleXMLElement;

class ImportWordpress extends Command
{
    protected $signature = 'gocarmat:import-wordpress
        {file : Caminho para o ficheiro XML de export do WordPress}
        {--skip-images : Não descarregar imagens de destaque}
        {--limit=0 : Importar apenas N posts (0 = todos), útil para testes}';

    protected $description = 'Importa artigos do export XML do WordPress: limpa shortcodes, migra SEO/Yoast, descarrega imagens e gera redirects 301.';

    private const NS = [
        'wp' => 'http://wordpress.org/export/1.2/',
        'content' => 'http://purl.org/rss/1.0/modules/content/',
        'excerpt' => 'http://wordpress.org/export/1.2/excerpt/',
    ];

    public function handle(): int
    {
        $file = $this->argument('file');

        if (! is_readable($file)) {
            $this->error("Ficheiro não encontrado: {$file}");

            return self::FAILURE;
        }

        $xml = simplexml_load_file($file, SimpleXMLElement::class, LIBXML_NOCDATA);
        $items = $xml->channel->item;

        // 1ª passagem: mapa de attachments (wp_id => URL da imagem)
        $attachments = [];
        foreach ($items as $item) {
            $wp = $item->children(self::NS['wp']);
            if ((string) $wp->post_type === 'attachment') {
                $attachments[(int) $wp->post_id] = (string) $wp->attachment_url;
            }
        }

        $this->info(count($attachments).' attachments mapeados.');

        $limit = (int) $this->option('limit');
        $imported = 0;
        $skippedImages = $this->option('skip-images');

        foreach ($items as $item) {
            $wp = $item->children(self::NS['wp']);

            if ((string) $wp->post_type !== 'post') {
                continue;
            }

            if ($limit > 0 && $imported >= $limit) {
                break;
            }

            $wpId = (int) $wp->post_id;
            $title = trim((string) $item->title);
            $slug = (string) $wp->post_name ?: Str::slug($title);
            $status = (string) $wp->status === 'publish' ? 'published' : 'draft';
            $publishedAt = $status === 'published' ? (string) $wp->post_date : null;
            $link = (string) $item->link;

            $rawBody = (string) $item->children(self::NS['content'])->encoded;
            $body = $this->cleanBody($rawBody);

            if (! $skippedImages) {
                $body = $this->localizeInlineImages($body, $slug);
            }

            // Meta Yoast + thumbnail
            $meta = [];
            foreach ($wp->postmeta as $pm) {
                $meta[(string) $pm->meta_key] = (string) $pm->meta_value;
            }

            $metaTitle = $meta['_yoast_wpseo_title'] ?? null;
            $metaTitle = $metaTitle ? $this->expandYoastVars($metaTitle, $title) : null;
            $metaDescription = $meta['_yoast_wpseo_metadesc'] ?? null;

            $excerpt = trim((string) $item->children(self::NS['excerpt'])->encoded)
                ?: $metaDescription
                ?: $this->excerptFromBody($body);

            // Imagem de destaque
            $featuredImage = null;
            $thumbId = (int) ($meta['_thumbnail_id'] ?? 0);
            if ($thumbId && isset($attachments[$thumbId])) {
                $featuredImage = $skippedImages
                    ? null
                    : $this->downloadImage($attachments[$thumbId], $slug);
            }

            $post = Post::updateOrCreate(
                ['wp_id' => $wpId],
                [
                    'title' => $title,
                    'slug' => $slug,
                    'excerpt' => Str::limit($excerpt, 400, '…'),
                    'body' => $body,
                    'featured_image' => $featuredImage,
                    'status' => $status,
                    'published_at' => $publishedAt,
                    'meta_title' => $metaTitle,
                    'meta_description' => $metaDescription ? Str::limit($metaDescription, 320, '') : null,
                ],
            );

            // Categorias
            $categoryIds = [];
            foreach ($item->category as $cat) {
                if ((string) $cat['domain'] !== 'category') {
                    continue;
                }
                $category = Category::firstOrCreate(
                    ['slug' => (string) $cat['nicename']],
                    ['name' => trim((string) $cat)],
                );
                $categoryIds[] = $category->id;
            }
            $post->categories()->sync($categoryIds);

            // Redirect 301 do URL antigo (na raiz) para /blog/{slug}
            $oldPath = rtrim((string) parse_url($link, PHP_URL_PATH), '/');
            $newPath = "/blog/{$slug}";
            if ($oldPath && $oldPath !== $newPath) {
                Redirect::updateOrCreate(
                    ['from_path' => $oldPath],
                    ['to_path' => $newPath],
                );
            }

            $imported++;
            $this->line("  [{$imported}] {$title}");
        }

        $this->info("Importados {$imported} artigos, ".Category::count().' categorias, '.Redirect::count().' redirects.');

        return self::SUCCESS;
    }

    /**
     * Limpa o HTML vindo do WordPress: remove shortcodes do tema,
     * atributos de estilo inline e spans vazios; aplica parágrafos.
     */
    private function cleanBody(string $html): string
    {
        // Remover shortcodes do tema ([column ...], [/column], [button ...], etc.)
        $html = preg_replace('/\[\/?[a-zA-Z0-9_-]+(?:\s[^\]]*)?\]/', '', $html);

        // Equivalente ao wpautop: duplas quebras de linha => parágrafos
        $blocks = preg_split("/\n\s*\n/", trim($html));
        $out = [];
        foreach ($blocks as $block) {
            $block = trim($block);
            if ($block === '') {
                continue;
            }
            // Não embrulhar blocos que já são elementos de bloco
            if (preg_match('/^<(h[1-6]|p|ul|ol|li|blockquote|table|figure|div|iframe|img)/i', $block)) {
                $out[] = $block;
            } else {
                $out[] = '<p>'.str_replace("\n", '<br>', $block).'</p>';
            }
        }
        $html = implode("\n", $out);

        // Remover atributos style/class/dir e spans decorativos
        // (sem consumir espaços adjacentes, para não colar palavras)
        $html = preg_replace('/\s(?:style|class|dir|lang)="[^"]*"/i', '', $html);
        $html = str_ireplace(['<span>', '</span>'], '', $html);

        // <b>/<i> => <strong>/<em>
        $html = str_ireplace(['<b>', '</b>', '<i>', '</i>'], ['<strong>', '</strong>', '<em>', '</em>'], $html);

        // Parágrafos vazios e &nbsp; soltos
        $html = preg_replace('/<p>(?:\s|&nbsp;)*<\/p>/i', '', $html);

        return trim($html);
    }

    /** Substitui variáveis do Yoast (%%title%%, %%sep%%, %%sitename%%). */
    private function expandYoastVars(string $template, string $title): string
    {
        return trim(strtr($template, [
            '%%title%%' => $title,
            '%%sep%%' => '·',
            '%%sitename%%' => 'GOCARMAT',
            '%%page%%' => '',
        ]));
    }

    /** Primeiro parágrafo do corpo, sem tags, para usar como excerto. */
    private function excerptFromBody(string $body): string
    {
        if (preg_match('/<p>(.*?)<\/p>/s', $body, $m)) {
            return trim(html_entity_decode(strip_tags($m[1])));
        }

        return Str::limit(trim(strip_tags($body)), 300, '');
    }

    /**
     * Descarrega as imagens inline do corpo (alojadas no WordPress antigo)
     * e reescreve os src para o storage local.
     */
    private function localizeInlineImages(string $html, string $slug): string
    {
        return preg_replace_callback(
            '/src="(https?:\/\/(?:www\.)?gocarmat\.pt\/wp-content\/uploads\/[^"]+)"/i',
            function (array $m) use ($slug) {
                $url = html_entity_decode($m[1]);
                $name = pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME);
                $ext = strtolower(pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)) ?: 'jpg';
                $path = "blog/inline/{$slug}-".Str::slug($name).".{$ext}";

                if (! Storage::disk('public')->exists($path)) {
                    try {
                        $tmp = tempnam(sys_get_temp_dir(), 'wpimg');
                        $response = Http::timeout(60)->retry(2, 1000)->sink($tmp)->get($url);
                        if (! $response->successful()) {
                            @unlink($tmp);

                            return $m[0]; // mantém o URL original se falhar
                        }
                        $stream = fopen($tmp, 'rb');
                        Storage::disk('public')->writeStream($path, $stream);
                        fclose($stream);
                        @unlink($tmp);
                    } catch (\Throwable $e) {
                        $this->warn("    imagem inline falhou: {$url}");

                        return $m[0];
                    }
                }

                return 'src="'.Storage::disk('public')->url($path).'"';
            },
            $html,
        );
    }

    /** Descarrega a imagem de destaque para storage/app/public/blog. */
    private function downloadImage(string $url, string $slug): ?string
    {
        $ext = strtolower(pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)) ?: 'jpg';
        $path = "blog/{$slug}.{$ext}";

        if (Storage::disk('public')->exists($path)) {
            return $path;
        }

        try {
            $tmp = tempnam(sys_get_temp_dir(), 'wpimg');
            $response = Http::timeout(60)->retry(2, 1000)->sink($tmp)->get($url);
            if (! $response->successful()) {
                $this->warn("    imagem falhou ({$response->status()}): {$url}");
                @unlink($tmp);

                return null;
            }
            $stream = fopen($tmp, 'rb');
            Storage::disk('public')->writeStream($path, $stream);
            fclose($stream);
            @unlink($tmp);

            return $path;
        } catch (\Throwable $e) {
            $this->warn("    imagem falhou: {$url} — {$e->getMessage()}");

            return null;
        }
    }
}
