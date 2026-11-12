<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * A campanha Pastilhas de Travão é exclusiva de duas oficinas (Foros da Amora e
 * Adroana): o formulário passa a ter um campo para escolher a oficina, e o
 * pedido segue para a oficina escolhida.
 *

 * O formulário fica mais alto com o campo extra, por isso sobe-se também o cartão.
 *
 * Só preenche se estiver vazio, para nunca pisar edições feitas no backoffice.
 */
return new class extends Migration
{
    private const SLUG = 'campanhas/pastilhas-travao';

    /** Pela ordem em que aparecem no campo. */
    private const OFICINAS = ['Foros da Amora', 'Adroana'];

    public function up(): void
    {
        $pagina = DB::table('pages')->where('slug', self::SLUG)->first();
        $blocos = $pagina ? json_decode((string) $pagina->content, true) : null;

        if (! is_array($blocos)) {
            return;
        }

        // Os ids podem variar entre ambientes: procura-se pelo nome.
        $ids = [];
        foreach (self::OFICINAS as $nome) {
            $id = DB::table('offices')->where('name', $nome)->value('id');

            if (! $id) {
                return;
            }

            $ids[] = (int) $id;
        }

        foreach ($blocos as &$bloco) {
            if ($bloco['type'] !== 'campanha_hero_marcacao') {
                continue;
            }

            if (blank($bloco['data']['oficinas_opcoes'] ?? null)) {
                $bloco['data']['oficinas_opcoes'] = $ids;
            }

            // Com o campo extra o formulário fica mais alto: sobe-se o cartão.
            if (! isset($bloco['data']['formulario_sobe'])) {
                $bloco['data']['formulario_sobe'] = true;
            }
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
