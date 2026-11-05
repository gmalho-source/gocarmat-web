<?php

use App\Support\ImagensDePaginas;
use App\Support\PaginasCampanha;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * As landing pages de campanha deixam de estar escritas em Blade e passam a ser
 * páginas da base de dados, editáveis no composer.
 *
 * - "layout": site completo (menu + rodapé) ou landing de campanha (sem ambos).
 * - As duas campanhas atuais são recriadas em blocos. Só se a página ainda não
 *   tiver o novo bloco, para nunca apagar edições feitas depois.
 * - As imagens de todas as páginas passam de public/images para o disco
 *   "public": o FileUpload do composer não reconhece as primeiras e apagava-as
 *   ao gravar a página (ver ImagensDePaginas).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('layout')->default('site')->after('slug');
        });

        foreach (PaginasCampanha::paginas() as $slug => $dados) {
            // A listagem já existe e pode ter sido editada: não se mexe no conteúdo.
            if ($slug === 'campanhas') {
                continue;
            }

            $existente = DB::table('pages')->where('slug', $slug)->first();

            if ($existente && str_contains((string) $existente->content, 'campanha_hero_marcacao')) {
                continue;
            }

            $valores = [
                'title' => $dados['title'],
                'layout' => $dados['layout'],
                'content' => $this->json($dados['content']),
                'meta_title' => $dados['meta_title'],
                'meta_description' => $dados['meta_description'],
                'status' => 'published',
                'updated_at' => now(),
            ];

            if ($existente) {
                DB::table('pages')->where('id', $existente->id)->update($valores);
            } else {
                DB::table('pages')->insert($valores + [
                    'slug' => $slug,
                    'published_at' => now(),
                    'show_in_menu' => false,
                    'menu_order' => 0,
                    'created_at' => now(),
                ]);
            }
        }

        foreach (DB::table('pages')->get() as $pagina) {
            $blocos = json_decode((string) $pagina->content, true);

            if (! is_array($blocos)) {
                continue;
            }

            $normalizados = ImagensDePaginas::normalizar($blocos);

            if ($normalizados !== $blocos) {
                DB::table('pages')->where('id', $pagina->id)->update(['content' => $this->json($normalizados)]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('layout');
        });
    }

    private function json(array $dados): string
    {
        return json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
