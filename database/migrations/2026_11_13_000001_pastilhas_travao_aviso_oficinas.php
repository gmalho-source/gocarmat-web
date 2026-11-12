<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Ajustes às campanhas exclusivas de oficinas:
 *
 * - Pastilhas de Travão: exclusiva das oficinas Foros da Amora e Adroana — aviso
 *   grande no topo do hero e título do formulário a referir as oficinas.
 * - Revisão Oficial: com o título e as notas mais compridos, o cartão do formulário
 *   passa por cima das caixas de baixo — sobe-se o cartão.
 *
 * Só preenche o que estiver vazio, para nunca pisar edições feitas no backoffice.
 */
return new class extends Migration
{
    private const AVISO = 'Campanha EXCLUSIVA para as Oficinas Foros da Amora e Adroana';

    private const TITULO_FORMULARIO = 'Marque a Revisão nas Oficinas Foros da Amora e Adroana';

    public function up(): void
    {
        $this->noHero('campanhas/pastilhas-travao', function (array $dados): array {
            if (blank($dados['aviso_topo'] ?? null)) {
                $dados['aviso_topo'] = self::AVISO;
            }

            if (($dados['formulario_titulo'] ?? null) === 'Marque a Substituição') {
                $dados['formulario_titulo'] = self::TITULO_FORMULARIO;
            }

            return $dados;
        });

        $this->noHero('campanhas/revisao-oficial', function (array $dados): array {
            if (! isset($dados['formulario_sobe'])) {
                $dados['formulario_sobe'] = true;
            }

            return $dados;
        });
    }

    public function down(): void
    {
        // Conteúdo editável: não se desfaz.
    }

    /** Aplica $ajuste aos dados do bloco hero com formulário da página. */
    private function noHero(string $slug, Closure $ajuste): void
    {
        $pagina = DB::table('pages')->where('slug', $slug)->first();
        $blocos = $pagina ? json_decode((string) $pagina->content, true) : null;

        if (! is_array($blocos)) {
            return;
        }

        foreach ($blocos as &$bloco) {
            if ($bloco['type'] === 'campanha_hero_marcacao') {
                $bloco['data'] = $ajuste($bloco['data'] ?? []);
            }
        }
        unset($bloco);

        DB::table('pages')->where('id', $pagina->id)->update([
            'content' => json_encode($blocos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'updated_at' => now(),
        ]);
    }
};
