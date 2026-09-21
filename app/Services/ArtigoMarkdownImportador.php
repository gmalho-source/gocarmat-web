<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;

/**
 * Converte o Markdown de um artigo importado no HTML usado em Post::body,
 * descarregando para armazenamento local qualquer imagem externa
 * referenciada — para o artigo nunca depender de um servidor de terceiros
 * que pode deixar de existir.
 */
class ArtigoMarkdownImportador
{
    public function paraHtml(string $markdown, string $slug): string
    {
        $markdown = $this->removerFrontMatter($markdown);
        $markdown = $this->removerPrimeiraLinha($markdown);

        $html = (new CommonMarkConverter())->convert($markdown)->getContent();

        return $this->descarregarImagensInline($html, $slug);
    }

    /**
     * A primeira linha do ficheiro (normalmente um título "# ...") dá o
     * título do artigo — não faz parte do corpo, para não ficar duplicado
     * (a página já mostra o título sozinha, por cima do corpo).
     */
    public function extrairTitulo(string $markdown): ?string
    {
        foreach (preg_split('/\R/', $this->removerFrontMatter($markdown)) as $linha) {
            $linha = trim($linha);

            if ($linha !== '') {
                return trim(preg_replace('/^#{1,6}\s*/', '', $linha));
            }
        }

        return null;
    }

    /** Remove um bloco de "front matter" (--- ... ---) no topo, se existir. */
    private function removerFrontMatter(string $markdown): string
    {
        return preg_replace('/^---\s*\n.*?\n---\s*\n/s', '', $markdown, 1) ?? $markdown;
    }

    /** Remove a primeira linha não vazia (o título, já extraído à parte). */
    private function removerPrimeiraLinha(string $markdown): string
    {
        $linhas = preg_split('/\R/', $markdown);

        foreach ($linhas as $i => $linha) {
            if (trim($linha) === '') {
                continue;
            }

            unset($linhas[$i]);

            return implode("\n", $linhas);
        }

        return $markdown;
    }

    private function descarregarImagensInline(string $html, string $slug): string
    {
        return preg_replace_callback(
            '/<img([^>]*)\ssrc="(https?:\/\/[^"]+)"([^>]*)>/i',
            function (array $m) use ($slug) {
                $url = html_entity_decode($m[2]);
                $nome = pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME) ?: Str::random(8);
                $path = $this->descarregarImagem($url, 'inline/'.$slug.'-'.Str::slug($nome));

                return $path
                    ? '<img'.$m[1].' src="'.Storage::disk('public')->url($path).'"'.$m[3].'>'
                    : $m[0];
            },
            $html,
        ) ?? $html;
    }

    private function descarregarImagem(string $url, string $nome): ?string
    {
        $ext = strtolower(pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)) ?: 'jpg';
        $path = "blog/{$nome}.{$ext}";

        if (Storage::disk('public')->exists($path)) {
            return $path;
        }

        try {
            $resposta = Http::timeout(60)->retry(2, 1000)->get($url);

            if (! $resposta->successful()) {
                Log::warning("importar-markdown: imagem falhou ({$resposta->status()}): {$url}");

                return null;
            }

            return Storage::disk('public')->put($path, $resposta->body()) ? $path : null;
        } catch (\Throwable $e) {
            Log::warning("importar-markdown: imagem falhou: {$url} - {$e->getMessage()}");

            return null;
        }
    }
}
