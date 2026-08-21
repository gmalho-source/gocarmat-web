<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

class SeedPages extends Command
{
    protected $signature = 'gocarmat:seed-pages
        {--force : Substituir páginas já existentes (apaga edições feitas no backoffice)}
        {--only= : Agir apenas sobre este slug (ex: --only=campanhas)}';

    protected $description = 'Cria na base de dados as páginas do site (Home, Sobre Nós, Serviços, EVA Powerlab e Marcações) em blocos editáveis, com o conteúdo atual.';

    public function handle(): int
    {
        $apenas = $this->option('only');

        foreach ($this->paginas() as $slug => $dados) {
            if ($apenas && $slug !== $apenas) {
                continue;
            }

            $existente = Page::where('slug', $slug)->first();

            if ($existente && ! $this->option('force')) {
                $this->warn("  {$slug}: já existe, ignorada (use --force para substituir)");

                continue;
            }

            Page::updateOrCreate(['slug' => $slug], $dados + [
                'status' => 'published',
                'published_at' => now(),
            ]);

            $this->info('  '.$slug.': '.count($dados['content']).' blocos');
        }

        $this->newLine();

        if ($apenas && ! array_key_exists($apenas, $this->paginas())) {
            $this->error("Não existe nenhuma página definida com o slug '{$apenas}'.");

            return self::FAILURE;
        }

        $this->info('Páginas disponíveis no backoffice em Páginas.');

        return self::SUCCESS;
    }

    private function paginas(): array
    {
        return [
            'home' => [
                'title' => 'Home',
                'meta_title' => 'GOCARMAT — Um serviço 360º para o seu carro · Oficinas multimarca na Grande Lisboa',
                'content' => [
                    ['type' => 'hero_home', 'data' => [
                        'eyebrow' => 'A sua oficina multimarca · Grande Lisboa',
                        'titulo' => 'Um serviço 360º para o seu carro.',
                        'texto' => 'Revisão oficial, pneus, colisão, climatização e assistência a elétricos — todas as marcas, nas 4 oficinas GOCARMAT da Grande Lisboa. Do combustão ao elétrico, com a confiança de 16 anos.',
                        'imagem' => 'images/hero.jpg',
                        'imagem_alt' => 'Cliente a carregar o seu carro elétrico',
                        'botoes' => [
                            ['texto' => 'Marcar Serviço', 'link' => '/marcacoes'],
                            ['texto' => 'Check-up Gratuito', 'link' => '/marcacoes'],
                        ],
                        'destaque_numero' => '+16',
                        'destaque_unidade' => 'ANOS',
                        'destaque_texto' => 'a cuidar de carros de todas as marcas, do combustão ao elétrico.',
                    ]],
                    ['type' => 'servicos_cards', 'data' => [
                        'titulo' => 'Os nossos serviços',
                        'botao_texto' => 'Conheça os nossos Serviços',
                        'botao_link' => '/servicos',
                        'itens' => [
                            ['numero' => '01.', 'etiqueta' => 'manutenção', 'titulo' => 'Revisão Oficial', 'imagem' => 'images/servico-revisao.jpg', 'link' => '/servicos',
                                'texto' => 'Já é possível fazer as revisões oficiais em oficina multimarca, mantendo a garantia do fabricante — com registo completo da intervenção.'],
                            ['numero' => '02.', 'etiqueta' => 'comodidade', 'titulo' => 'Inspeção', 'imagem' => 'images/servico-inspecao.jpg', 'link' => '/servicos',
                                'texto' => 'A pensar na sua comodidade, acompanhamos o seu carro ao centro de inspeção — deixa o carro connosco e nós tratamos de tudo.'],
                            ['numero' => '03.', 'etiqueta' => 'segurança', 'titulo' => 'Pneus', 'imagem' => 'images/servico-pneus.jpg', 'link' => '/servicos',
                                'texto' => 'Pneus das melhores marcas, com montagem, alinhamento de direção e equilibragem incluídos.'],
                            ['numero' => '04.', 'etiqueta' => 'manutenção', 'titulo' => 'Colisão e Pintura', 'imagem' => 'images/servico-colisao.jpg', 'link' => '/servicos',
                                'texto' => 'O nosso moderno Centro de Colisão e Pintura repara todo o tipo de danos de carroçaria, em veículos de combustão e elétricos — deixou de haver motivo para andar com mossas.'],
                        ],
                    ]],
                    ['type' => 'eva_banner', 'data' => [
                        'etiqueta' => 'serviços eva powerlab',
                        'titulo' => 'EVA POWERLAB',
                        'subtitulo' => 'O laboratório de mobilidade elétrica da GOCARMAT',
                        'texto' => 'Diagnóstico, reparação e certificação de baterias de alta tensão — para BEV, HEV e PHEV de todas as marcas, ao abrigo do regulamento europeu (MV-BER 461/2010) e sem perder a garantia.',
                        'botao_texto' => 'Conhecer o EVA Powerlab',
                        'botao_link' => '/eva-powerlab',
                        'servicos' => [
                            ['texto' => 'EVA Lab — diagnóstico de BMS'],
                            ['texto' => 'Rescue — desbloqueio EV'],
                            ['texto' => 'Tesla Independent Service'],
                            ['texto' => 'EVA Collision'],
                            ['texto' => 'Battery Warranty · até 5 anos'],
                            ['texto' => 'Certificação MV-BER'],
                        ],
                    ]],
                    ['type' => 'oficinas_titulo', 'data' => [
                        'titulo' => '4 oficinas - o mesmo cuidado',
                        'botao_texto' => 'Marcar na Oficina mais próxima',
                        'botao_link' => '/marcacoes',
                    ]],
                    ['type' => 'blog_grelha', 'data' => [
                        'titulo' => 'Gocarmat blog',
                        'botao_texto' => 'Ver todos os Artigos',
                    ]],
                ],
            ],

            'sobre-nos' => [
                'title' => 'Sobre Nós',
                'meta_title' => 'Sobre Nós — GOCARMAT · Uma rede de oficinas 100% portuguesa',
                'meta_description' => 'A GOCARMAT é uma rede de oficinas multimarca com 16 anos de experiência, capitais 100% portugueses e um laboratório próprio de mobilidade elétrica, o EVA Powerlab.',
                'content' => [
                    ['type' => 'hero_split', 'data' => [
                        'eyebrow' => 'Quem somos',
                        'titulo' => 'Uma rede de oficinas 100% portuguesa.',
                        'texto' => 'A GOCARMAT é uma rede de oficinas multimarca com 16 anos de experiência, a operar na Grande Lisboa e a crescer para todo o país. Capitais 100% portugueses, uma equipa técnica de referência — e um laboratório próprio de mobilidade elétrica, o EVA Powerlab.',
                        'imagem' => 'images/servico-revisao.jpg',
                        'fundo' => 'carbono',
                        'overlay_azul' => true,
                        'proporcao' => '55',
                        'cartao_titulo' => '4 oficinas',
                        'cartao_texto' => 'na Grande Lisboa — e a crescer para todo o país.',
                        'botoes' => [
                            ['texto' => 'Marcar Serviço', 'link' => '/marcacoes'],
                            ['texto' => 'Conheça os nossos Serviços', 'link' => '/servicos'],
                        ],
                    ]],
                    ['type' => 'missao_visao', 'data' => [
                        'titulo_1' => 'Missão',
                        'texto_1' => 'Contribuir para a segurança rodoviária e o aumento da vida útil dos veículos, através de serviços de excelência na manutenção automóvel.',
                        'titulo_2' => 'Visão',
                        'texto_2' => 'Ser o parceiro de referência de particulares e empresas na manutenção do automóvel — de todas as marcas, do combustão ao elétrico.',
                    ]],
                    ['type' => 'valores', 'data' => [
                        'titulo' => 'Os nossos valores',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Transparência', 'texto' => 'Sabe antecipadamente o valor a pagar, pode assistir a todas as reparações e é aconselhado pelos nossos técnicos.'],
                            ['numero' => '02', 'titulo' => 'Inovação e confiança', 'texto' => 'Toda a informação é prestada de forma simples, para que a compreenda da melhor maneira possível.'],
                            ['numero' => '03', 'titulo' => 'Qualidade certificada', 'texto' => 'Escolhemos peças dos melhores fabricantes e registamos cada intervenção. Qualidade que se comprova.'],
                            ['numero' => '04', 'titulo' => 'Rapidez e eficiência', 'texto' => 'Cada viatura tem uma ficha de diagnóstico que melhora continuamente os tempos de reparação.'],
                            ['numero' => '05', 'titulo' => 'Bem-estar dos clientes', 'texto' => 'Podemos ir buscar e entregar a viatura em casa ou no local de trabalho. Contacte-nos para saber mais.'],
                        ],
                        'destaque_titulo' => 'Horário alargado',
                        'destaque_texto' => 'Segunda a sexta das 9h às 19h e sábados das 9h às 13h.',
                        'destaque_botao' => 'Marcar Serviço',
                        'destaque_link' => '/marcacoes',
                    ]],
                    ['type' => 'oficinas_titulo', 'data' => ['titulo' => '4 oficinas - o mesmo cuidado']],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Vamos buscar e entregar o seu carro',
                        'texto' => 'Recolhemos e entregamos a sua viatura em casa ou no local de trabalho — contacte-nos para saber mais.',
                        'icone' => 'car-burst',
                        'cor_icone' => 'energia',
                        'cor_titulo' => 'energia',
                        'botao_texto' => 'Marcar Serviço',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                    ]],
                ],
            ],

            'servicos' => [
                'title' => 'Serviços',
                'meta_title' => 'Serviços — GOCARMAT · Tudo o que o seu carro precisa, num só lugar',
                'meta_description' => 'Serviço 360 GOCARMAT: revisão oficial, inspeção, pneus, colisão e pintura, climatização, óleo e filtros — com orçamentos grátis e check-up gratuito.',
                'content' => [
                    ['type' => 'hero_split', 'data' => [
                        'eyebrow' => 'Os nossos serviços',
                        'titulo' => 'Tudo o que o seu carro precisa, num só lugar.',
                        'texto' => 'Serviço 360: seja qual for o problema, a GOCARMAT resolve — com orçamentos grátis e check-up gratuito.',
                        'imagem' => 'images/servico-inspecao.jpg',
                        'fundo' => 'energia',
                        'proporcao' => '62',
                    ]],
                    ['type' => 'servicos_detalhe', 'data' => [
                        'itens' => [
                            ['numero' => '01.', 'etiqueta' => 'manutenção', 'variante' => 'gelo', 'titulo' => 'Revisão Oficial', 'imagem' => 'images/servico-revisao.jpg',
                                'texto' => 'Já é possível fazer as revisões oficiais em oficina multimarca, mantendo a garantia do fabricante — com registo completo da intervenção.',
                                'bullets' => [['texto' => 'Plano de revisão do fabricante'], ['texto' => 'Peças de qualidade equivalente'], ['texto' => 'Check-up gratuito incluído']]],
                            ['numero' => '02.', 'etiqueta' => 'comodidade', 'variante' => 'branco', 'titulo' => 'Inspeção', 'imagem' => 'images/servico-inspecao.jpg',
                                'texto' => 'A pensar na sua comodidade, acompanhamos o seu carro ao centro de inspeção — deixa o carro connosco e nós tratamos de tudo.',
                                'bullets' => [['texto' => 'Acompanhamento ao centro'], ['texto' => 'Pré-inspeção incluída'], ['texto' => 'Resolução de anomalias no próprio dia']]],
                            ['numero' => '03.', 'etiqueta' => 'segurança', 'variante' => 'azul', 'titulo' => 'Pneus', 'imagem' => 'images/servico-pneus.jpg',
                                'texto' => 'Pneus das melhores marcas ao melhor preço, com alinhamento de direção e equilibragem incluídos.',
                                'bullets' => [['texto' => 'Todas as marcas e medidas'], ['texto' => 'Alinhamento e equilibragem'], ['texto' => 'Verificação gratuita de desgaste']]],
                            ['numero' => '04.', 'etiqueta' => 'carroçaria', 'variante' => 'branco', 'titulo' => 'Colisão e Pintura', 'imagem' => 'images/servico-colisao.jpg',
                                'texto' => 'O nosso moderno Centro de Colisão e Pintura repara todo o tipo de danos de carroçaria — deixou de haver justificação para andar com mossas e riscos.',
                                'bullets' => [['texto' => 'Orçamento gratuito'], ['texto' => 'Gestão do processo com seguradoras'], ['texto' => 'Pintura com acabamento de fábrica']]],
                            ['numero' => '05.', 'etiqueta' => 'conforto', 'variante' => 'gelo', 'titulo' => 'Climatização', 'imagem' => 'images/servico-climatizacao.jpg',
                                'texto' => 'Manutenção completa do sistema de climatização e ar condicionado, para viajar com conforto e ar saudável em todas as estações.',
                                'bullets' => [['texto' => 'Carga e verificação de gás'], ['texto' => 'Higienização do habitáculo'], ['texto' => 'Deteção e reparação de fugas']]],
                            ['numero' => '06.', 'etiqueta' => 'manutenção', 'variante' => 'azul', 'titulo' => 'Óleo, Filtros e Mecânica', 'imagem' => 'images/servico-oleo.jpg',
                                'texto' => 'Mudança de óleo com todos os filtros (óleo, combustível, ar e habitáculo) e mecânica geral de reparação.',
                                'bullets' => [['texto' => 'Óleos homologados pelo fabricante'], ['texto' => 'Todos os filtros incluídos'], ['texto' => 'Mecânica e diagnóstico geral']]],
                        ],
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Tem um elétrico ou híbrido?',
                        'texto' => 'Conte com a assistência especializada da GOCARMAT.',
                        'botao_texto' => 'Conhecer o EVA Powerlab',
                        'botao_link' => '/eva-powerlab',
                        'fundo' => 'lima',
                        'colar_ao_rodape' => true,
                    ]],
                ],
            ],

            'eva-powerlab' => [
                'title' => 'EVA Powerlab',
                'meta_title' => 'EVA Powerlab — Assistência a veículos elétricos · GOCARMAT',
                'meta_description' => 'O laboratório de mobilidade elétrica da GOCARMAT: diagnóstico, reparação e certificação de baterias de alta tensão para BEV, HEV e PHEV, ao abrigo do regulamento MV-BER 461/2010.',
                'content' => [
                    ['type' => 'hero_split', 'data' => [
                        'eyebrow' => 'EVA Powerlab — Assistência a veículos eletrificados',
                        'titulo' => 'O seu elétrico tem oficina',
                        'titulo_destaque' => 'fora da marca.',
                        'texto' => 'O EVA Powerlab é o laboratório de mobilidade elétrica da GOCARMAT: diagnóstico, reparação e certificação de baterias de alta tensão (BEV, HEV e PHEV). Ao abrigo do regulamento europeu MV-BER 461/2010, mantém a garantia — sem depender da marca.',
                        'imagem' => 'images/eva-hero.jpg',
                        'fundo' => 'carbono',
                        'proporcao' => '58',
                        'botoes' => [['texto' => 'Marcação EVA', 'link' => '/marcacoes']],
                    ]],
                    ['type' => 'eva_servicos', 'data' => [
                        'itens' => [
                            ['etiqueta' => 'lab', 'icone' => 'bolt', 'titulo' => 'EVA LAB', 'texto' => 'Diagnóstico ao sistema de gestão da bateria (BMS), módulo a módulo. Descobrimos o estado de saúde real (SoH) do pack.'],
                            ['etiqueta' => 'resgate', 'icone' => 'car-burst', 'titulo' => 'RESCUE', 'texto' => 'Desbloqueio e assistência a elétricos imobilizados. Resposta rápida para voltar a pôr o carro na estrada.'],
                            ['etiqueta' => 'segurança', 'icone' => 'car-burst', 'titulo' => 'EVA COLLISION', 'texto' => 'Colisão e pintura para elétricos e híbridos, com os cuidados de alta tensão que estes veículos exigem.'],
                            ['etiqueta' => 'tis', 'icone' => 'bolt', 'titulo' => 'TESLA INDEPENDENT SERVICE', 'texto' => 'Manutenção e reparação de Tesla com equipamento dedicado. Ao nível da marca, sem depender da marca.'],
                            ['etiqueta' => 'garantia', 'icone' => 'shield', 'titulo' => 'EVA BATTERY WARRANTY', 'texto' => 'Garantia até 5 anos nas baterias recuperadas e certificadas no EVA Powerlab.'],
                            ['etiqueta' => 'mv-ber', 'icone' => 'certificate', 'titulo' => 'CERTIFICAÇÃO MV-BER', 'texto' => 'Certificamos a saúde da bateria e registamos a intervenção no Livro de Manutenção Digital. Prova e valor de revenda.'],
                        ],
                    ]],
                    ['type' => 'porque_faq', 'data' => [
                        'titulo' => 'Porquê o EVA Powerlab?',
                        'texto' => 'Trabalhar em alta tensão exige formação, equipamento e certificação próprios. Na EVA, cada intervenção fica registada no Livro de Manutenção Digital (LMD).',
                        'imagem' => 'images/eva-car-profile.png',
                        'faq_titulo' => 'Perguntas frequentes',
                        'faqs' => [
                            ['pergunta' => 'Posso fazer a manutenção do meu elétrico fora da marca?', 'resposta' => 'Sim. Ao abrigo do regulamento europeu MV-BER 461/2010, pode fazer a manutenção em oficinas independentes qualificadas sem perder a garantia — desde que sejam seguidos os planos do fabricante, como fazemos na EVA Powerlab.'],
                            ['pergunta' => 'Que cuidados têm com baterias de alta tensão?', 'resposta' => 'Todas as intervenções em alta tensão são executadas por técnicos com formação e certificação próprias, com equipamento dedicado e protocolos de segurança específicos — e ficam registadas no Livro de Manutenção Digital.'],
                            ['pergunta' => 'Fazem assistência a Tesla?', 'resposta' => 'Sim. Com o Tesla Independent Service fazemos manutenção e reparação de Tesla com equipamento dedicado, ao nível da marca — sem depender da marca.'],
                            ['pergunta' => 'A garantia da bateria pode ser estendida?', 'resposta' => 'Sim. As baterias recuperadas e certificadas no EVA Powerlab incluem garantia até 5 anos.'],
                        ],
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Marcação EVA',
                        'texto' => 'Diagnóstico, reparação e certificação de baterias — marque já a sua avaliação.',
                        'icone' => 'bolt',
                        'cor_icone' => 'lima',
                        'botao_texto' => 'Marcar agora',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                    ]],
                ],
            ],

            'marcacoes' => [
                'title' => 'Marcações',
                'meta_title' => 'Marcações — GOCARMAT · Marque o seu serviço online',
                'meta_description' => 'Marque online o serviço para o seu carro numa das 4 oficinas GOCARMAT da Grande Lisboa: revisão oficial, pneus, inspeção, colisão e assistência a elétricos.',
                'content' => [
                    ['type' => 'marcacoes_form', 'data' => [
                        'eyebrow' => 'Contactos / Marcações',
                        'titulo' => 'Marcações',
                        'botao_texto' => 'Enviar',
                        'sucesso_titulo' => 'Pedido enviado com sucesso!',
                        'sucesso_texto' => 'Recebemos a sua marcação e enviámos um e-mail de confirmação. A nossa equipa entrará em contacto consigo brevemente.',
                        'newsletter_texto' => 'Quero subscrever a newsletter GOCARMAT e receber dicas e campanhas.',
                        'rgpd_texto' => 'Aceito e dou o meu consentimento para a recolha e tratamento dos meus dados pessoais (RGPD), usados exclusivamente pela GOCARMAT para responder a este pedido. Consulte a',
                    ]],
                    ['type' => 'contactos_lista', 'data' => [
                        'titulo' => 'Outros contactos',
                        'itens' => [
                            ['label' => 'Apoio ao Cliente', 'email' => 'apoiocliente@gocarmat.pt'],
                            ['label' => 'Quer trabalhar na Equipa Gocarmat', 'email' => 'recrutamento@gocarmat.pt'],
                            ['label' => 'Fornecedores / Outros assuntos', 'email' => 'geral@gocarmat.pt'],
                        ],
                    ]],
                    ['type' => 'oficinas_titulo', 'data' => ['titulo' => '4 oficinas - o mesmo cuidado']],
                ],
            ],

            'teste' => [
                'title' => 'Teste',
                'meta_title' => 'Teste — GOCARMAT',
                'content' => [
                    ['type' => 'hero', 'data' => [
                        'eyebrow' => 'Teste',
                        'titulo' => 'Página de teste',
                        'fundo' => 'energia',
                    ]],
                ],
            ],
        ];
    }
}
