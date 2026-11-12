<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * A campanha Revisão Oficial é exclusiva da oficina Foros da Amora: aviso grande
 * no topo, referência nas notas do pedido (formulário do hero e botão final) e
 * pedido encaminhado para essa oficina, sem o botão "Ver outras Campanhas" e com o título do formulário a referir a oficina.
 *
 * Só preenche o que estiver vazio, para nunca pisar edições feitas no backoffice.
 */
return new class extends Migration
{
    private const SLUG = 'campanhas/revisao-oficial';

    private const AVISO = 'Campanha EXCLUSIVA para a Oficina Foros da Amora';

    private const NOTA_ANTIGA = 'Campanha: Revisão Oficial por 94,90€';

    private const NOTA = 'Campanha EXCLUSIVA para a Oficina Foros da Amora — Revisão Oficial por 94,90€';

    public function up(): void
    {
        $pagina = DB::table('pages')->where('slug', self::SLUG)->first();

        if (! $pagina) {
            return;
        }

        $blocos = json_decode((string) $pagina->content, true);

        if (! is_array($blocos)) {
            return;
        }

        $oficina = DB::table('offices')->where('name', 'Foros da Amora')->value('id');

        foreach ($blocos as &$bloco) {
            $dados = $bloco['data'] ?? [];

            if ($bloco['type'] === 'campanha_hero_marcacao') {
                $dados['aviso_topo'] = filled($dados['aviso_topo'] ?? null) ? $dados['aviso_topo'] : self::AVISO;
                $dados['notas_predefinidas'] = filled($dados['notas_predefinidas'] ?? null) ? $dados['notas_predefinidas'] : self::NOTA;

                if ($oficina && blank($dados['office_id'] ?? null)) {
                    $dados['office_id'] = $oficina;
                }

                if (($dados['formulario_titulo'] ?? null) === 'Marque a sua Revisão') {
                    $dados['formulario_titulo'] = 'Marque a Revisão na Oficina Foros da Amora';
                }

                // Campanha exclusiva de uma oficina: sem o botão para as outras campanhas.
                $botoes = $dados['botoes'] ?? [];
                if (count($botoes) === 1 && ($botoes[0]['link'] ?? null) === '/campanhas') {
                    $dados['botoes'] = [];
                }
            }

            // O botão final leva a /marcacoes com a nota no URL.
            if ($bloco['type'] === 'cta_icone' && str_contains((string) ($dados['botao_link'] ?? ''), rawurlencode(self::NOTA_ANTIGA))) {
                $dados['botao_link'] = str_replace(rawurlencode(self::NOTA_ANTIGA), rawurlencode(self::NOTA), $dados['botao_link']);
            }

            $bloco['data'] = $dados;
        }
        unset($bloco);

        DB::table('pages')->where('id', $pagina->id)->update([
            'content' => json_encode($blocos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Conteúdo editável: não se desfaz.
    }
};
