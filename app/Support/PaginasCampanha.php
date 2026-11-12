<?php

namespace App\Support;

/**
 * Conteúdo das páginas de campanha (listagem + as duas campanhas atuais) como
 * blocos do composer. Usado pela migração que as passa para a base de dados e
 * pelo gocarmat:seed-pages, para uma instalação nova ficar igual.
 *
 * As imagens são copiadas para o disco "public" (ver ImagensDePaginas).
 */
class PaginasCampanha
{
    /** @return array<string, array<string, mixed>> */
    public static function paginas(): array
    {
        return array_map(function (array $pagina) {
            $pagina['content'] = ImagensDePaginas::normalizar($pagina['content']);

            return $pagina;
        }, self::definicoes());
    }

    /** @return array<string, array<string, mixed>> */
    private static function definicoes(): array
    {
        return [
            'campanhas' => [
                'title' => 'Campanhas',
                'layout' => 'site',
                'meta_title' => 'Campanhas — GOCARMAT · Promoções ativas',
                'meta_description' => 'Campanhas e promoções ativas da GOCARMAT — revisão oficial e substituição de pastilhas de travão a preços especiais.',
                'content' => [
                    ['type' => 'campanhas_grelha', 'data' => [
                        'titulo' => 'Gocarmat Campanhas',
                        'itens' => [
                            [
                                'titulo' => 'Revisão Oficial por apenas 94,90€',
                                'preco' => '94,90€',
                                'texto' => 'Substituição do óleo do motor, filtro de óleo, filtro de ar e check-up completo à viatura — mão de obra incluída.',
                                'condicoes' => 'Promoção válida para a generalidade das marcas, excluindo marcas de luxo (Ferrari, Maserati, Lamborghini, Porsche, Aston Martin, Jaguar, Bentley, Lexus, BMW série 5/7, Audi A5/A6/A7/A8, Mercedes classe E/S). Monovolumes e SUV sujeitos a suplemento. Não acumulável com outras campanhas ativas.',
                                'link' => '/campanhas/revisao-oficial',
                                'imagem' => 'images/campanha-revisao-oficial.jpg',
                            ],
                            [
                                'titulo' => 'Substituição de Pastilhas de Travão por 84,90€',
                                'preco' => '84,90€',
                                'texto' => 'Substituição de um jogo de pastilhas de travão (eixo dianteiro ou traseiro), com check-up incluído. Não inclui sensores de desgaste.',
                                'condicoes' => 'Não acumulável com outras campanhas ativas. Exclui marcas de luxo e premium; monovolumes e 4x4 sujeitos a suplemento.',
                                'link' => '/campanhas/pastilhas-travao',
                                'imagem' => 'images/campanha-pastilhas-travao.jpg',
                            ],
                        ],
                    ]],
                ],
            ],

            'campanhas/revisao-oficial' => [
                'title' => 'Campanha — Revisão Oficial',
                'layout' => 'landing',
                'meta_title' => 'Revisão Oficial por 94,90€ — Campanha GOCARMAT',
                'meta_description' => 'Revisão oficial com óleo, filtro de óleo, filtro de ar e check-up completo, por apenas 94,90€ — mão de obra incluída.',
                'content' => [
                    ['type' => 'campanha_cta_fixo', 'data' => [
                        'titulo' => 'Pronto para marcar?',
                        'texto' => 'Marque já a sua revisão oficial com este preço especial.',
                        'icone' => 'car',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Agora',
                        'botao_link' => '#form-marcacao',
                        'mobile_so_botao' => true,
                    ]],
                    ['type' => 'campanha_hero_marcacao', 'data' => [
                        'breadcrumb_pai' => 'Campanhas',
                        'breadcrumb_atual' => 'Revisão Oficial',
                        'titulo' => 'Revisão oficial por apenas 94,90€.',
                        'texto' => 'Substituição do óleo do motor, filtro de óleo e filtro de ar, com check-up completo à viatura — mão de obra incluída.',
                        'imagem' => 'images/campanha-revisao-oficial.jpg',
                        'botoes' => [],
                        'faixa_numero' => '94,90€',
                        'faixa_texto' => 'Preço fixo, com mão de obra incluída — válido para a generalidade das marcas.',
                        'formulario_titulo' => 'Marque a Revisão na Oficina Foros da Amora',
                        'aviso_topo' => 'Campanha EXCLUSIVA para a Oficina Foros da Amora',
                        'notas_predefinidas' => 'Campanha EXCLUSIVA para a Oficina Foros da Amora — Revisão Oficial por 94,90€',
                        'formulario_sobe' => true,
                        'servico' => 'Revisão Oficial',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'titulo' => 'O que está incluído nesta ',
                        'titulo_destaque' => 'campanha',
                        'sobre_hero' => true,
                        'itens' => [
                            ['numero' => '01.', 'titulo' => 'Óleo do motor', 'texto' => 'Substituição do óleo do motor (5W30 / 5W40 / 10W40, até 5 litros).'],
                            ['numero' => '02.', 'titulo' => 'Filtro de óleo', 'texto' => 'Substituição do filtro de óleo.'],
                            ['numero' => '03.', 'titulo' => 'Filtro de ar', 'texto' => 'Substituição do filtro de ar.'],
                            ['numero' => '04.', 'titulo' => 'Check-up completo', 'texto' => 'Verificação do sistema de travagem, arranque e carga, iluminação, direção e suspensão.'],
                            ['numero' => '05.', 'titulo' => 'Diagnóstico digital', 'texto' => 'Diagnóstico EOBD digital, para viaturas a partir de 2010.'],
                            ['numero' => '06.', 'titulo' => 'Mão de obra incluída', 'texto' => 'Sem custos adicionais de mão de obra no serviço realizado.'],
                        ],
                    ]],
                    self::checkup('images/campanha-revisao-oficial-checkup.jpg'),
                    self::condicoes(
                        'Promoção válida para a generalidade das marcas, com mão de obra incluída.<br>'
                    ),
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Precisa de trocar as pastilhas?',
                        'texto' => 'Marque já a substituição com este preço especial.',
                        'icone' => 'car',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Agora',
                        'botao_link' => '/marcacoes?servico='.rawurlencode('Outro assunto').'&nota='.rawurlencode('Campanha EXCLUSIVA para a Oficina Foros da Amora — Revisão Oficial por 94,90€'),
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                        'ligar_barra_fixa' => true,
                    ]],
                    ['type' => 'campanha_rodape', 'data' => []],
                ],
            ],

            'campanhas/pastilhas-travao' => [
                'title' => 'Campanha — Pastilhas de Travão',
                'layout' => 'landing',
                'meta_title' => 'Pastilhas de Travão por 84,90€ — Campanha GOCARMAT',
                'meta_description' => 'Substituição de um jogo de pastilhas de travão, com check-up incluído, por apenas 84,90€.',
                'content' => [
                    ['type' => 'campanha_cta_fixo', 'data' => [
                        'titulo' => 'Precisa de trocar as pastilhas?',
                        'texto' => 'Marque já a substituição com este preço especial.',
                        'icone' => 'car',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Agora',
                        'botao_link' => '#form-marcacao',
                        'mobile_so_botao' => true,
                    ]],
                    ['type' => 'campanha_hero_marcacao', 'data' => [
                        'breadcrumb_pai' => 'Campanhas',
                        'aviso_topo' => 'Campanha EXCLUSIVA para as Oficinas Foros da Amora e Adroana',
                        'breadcrumb_atual' => 'Pastilhas de Travão',
                        'titulo' => 'Pastilhas de travão por apenas 84,90€.',
                        'texto' => 'Substituição de um jogo de pastilhas de travão (eixo dianteiro ou traseiro), com check-up incluído.',
                        'imagem' => 'images/campanha-pastilhas-travao.jpg',
                        'botoes' => [
                            ['texto' => 'Ver outras Campanhas', 'link' => '/campanhas'],
                        ],
                        'faixa_numero' => '84,90€',
                        'faixa_texto' => 'Preço fixo por um jogo de pastilhas de travão, com check-up incluído.',
                        'formulario_titulo' => 'Marque a Revisão nas Oficinas Foros da Amora e Adroana',
                        'servico' => 'Outro assunto',
                        'notas_predefinidas' => 'Campanha: Pastilhas de Travão por 84,90€',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'titulo' => 'O que está incluído nesta ',
                        'titulo_destaque' => 'campanha',
                        'sobre_hero' => true,
                        'itens' => [
                            ['numero' => '01.', 'titulo' => 'Pastilhas de travão', 'texto' => 'Substituição de um jogo de pastilhas de travão, no eixo dianteiro ou traseiro.'],
                            ['numero' => '02.', 'titulo' => 'Inspeção do disco', 'texto' => 'Avaliação do estado dos discos de travão associados.'],
                            ['numero' => '03.', 'titulo' => 'Verificação do fluído', 'texto' => 'Verificação do nível e estado do fluído de travões.'],
                            ['numero' => '04.', 'titulo' => 'Check-up geral', 'texto' => 'Verificação do sistema de arranque e carga, iluminação, direção e suspensão.'],
                            ['numero' => '05.', 'titulo' => 'Diagnóstico digital', 'texto' => 'Diagnóstico EOBD digital, para viaturas a partir de 2010.'],
                            ['numero' => '06.', 'titulo' => 'Sem sensores de desgaste', 'texto' => 'O preço não inclui sensores de desgaste, quando aplicável à sua viatura.'],
                        ],
                    ]],
                    self::checkup('images/campanha-pastilhas-travao.jpg'),
                    self::condicoes(
                        'Preço fixo por um jogo de pastilhas de travão (eixo dianteiro ou traseiro), com check-up incluído.<br>'
                        .'Esta campanha não inclui os avisadores de desgaste das pastilhas.<br>'
                    ),
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Precisa de trocar as pastilhas?',
                        'texto' => 'Marque já a substituição com este preço especial.',
                        'icone' => 'car',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Agora',
                        'botao_link' => '/marcacoes?servico='.rawurlencode('Outro assunto').'&nota='.rawurlencode('Campanha: Pastilhas de Travão por 84,90€'),
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                        'ligar_barra_fixa' => true,
                    ]],
                    ['type' => 'campanha_rodape', 'data' => []],
                ],
            ],
        ];
    }

    /** Bloco "check-up incluído", igual nas duas campanhas. */
    private static function checkup(string $imagem): array
    {
        return ['type' => 'campanha_checkup', 'data' => [
            'titulo' => 'Todos os serviços GOCARMAT em promoção incluem um check-up à sua viatura',
            'texto' => 'O check-up engloba a verificação de:',
            'imagem' => $imagem,
            'itens' => [
                ['texto' => 'Sistema de travagem (discos, pastilhas, calços, maxilas e líquido dos travões)'],
                ['texto' => 'Sistema de arranque e carga (bateria, alternador e motor de arranque)'],
                ['texto' => 'Sistema de iluminação, lâmpadas e óticas, piscas e iluminação da matrícula'],
                ['texto' => 'Sistema de direção e suspensão/amortecedores'],
                ['texto' => 'Diagnóstico digital EOBD (conjunto de testes imprescindíveis para a deteção de avarias na sua viatura, para veículos a partir de 2010)'],
            ],
        ]];
    }

    /** Bloco "Condições": as primeiras linhas variam por campanha, o resto é comum. */
    private static function condicoes(string $inicio): array
    {
        return ['type' => 'texto', 'data' => [
            'titulo' => 'Condições',
            'fundo' => 'branco',
            'corpo' => '<p>'.$inicio
                .'As promoções indicadas não são acumuláveis com outras campanhas ou promoções em vigor, salvo quando previamente autorizado pela GOCARMAT.<br>'
                .'A promoção não é válida para as viaturas Ferrari, Maserati, Lamborghini, Porsche, Aston Martin, Jaguar, Bentley, Lexus, BMW série 5 e 7, Audi A5, A6, A7 e A8, Mercedes Classe E/S/5/6/7, monovolumes e jipes. Monovolumes, autocaravanas e jipes poderão ser incluídos mediante um suplemento pago diretamente no local — informe-se deste valor, pois pode haver oscilações de acordo com a marca e modelo da sua viatura.<br>'
                .'A GOCARMAT reserva-se no direito de incluir ou excluir qualquer viatura não mencionada acima. Consulte a oficina para mais detalhes.</p>',
        ]];
    }
}
