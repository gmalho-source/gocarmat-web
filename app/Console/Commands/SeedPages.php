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
                            ['numero' => '01.', 'etiqueta' => 'manutenção', 'titulo' => 'Revisão Oficial', 'imagem' => 'images/servico-revisao.jpg', 'link' => '/servicos/revisao-oficial',
                                'texto' => 'Já é possível fazer as revisões oficiais em oficina multimarca, mantendo a garantia do fabricante — com registo completo da intervenção.'],
                            ['numero' => '02.', 'etiqueta' => 'comodidade', 'titulo' => 'Inspeção', 'imagem' => 'images/servico-inspecao.jpg', 'link' => '/servicos/inspecao',
                                'texto' => 'A pensar na sua comodidade, acompanhamos o seu carro ao centro de inspeção — deixa o carro connosco e nós tratamos de tudo.'],
                            ['numero' => '03.', 'etiqueta' => 'segurança', 'titulo' => 'Pneus', 'imagem' => 'images/servico-pneus.jpg', 'link' => '/servicos/pneus',
                                'texto' => 'Pneus das melhores marcas, com montagem, alinhamento de direção e equilibragem incluídos.'],
                            ['numero' => '04.', 'etiqueta' => 'manutenção', 'titulo' => 'Colisão e Pintura', 'imagem' => 'images/servico-colisao.jpg', 'link' => '/servicos/colisao-e-pintura',
                                'texto' => 'O nosso moderno Centro de Colisão e Pintura repara todo o tipo de danos de carroçaria, em veículos de combustão e elétricos — deixou de haver motivo para andar com mossas.'],
                            ['numero' => '05.', 'etiqueta' => 'conforto', 'titulo' => 'Climatização', 'imagem' => 'images/servico-climatizacao.jpg', 'link' => '/servicos/climatizacao',
                                'texto' => 'Manutenção completa do sistema de climatização e ar condicionado, para viajar com conforto e ar saudável em todas as estações.'],
                            ['numero' => '06.', 'etiqueta' => 'manutenção', 'titulo' => 'Óleo, Filtros e Mecânica', 'imagem' => 'images/servico-oleo.jpg', 'link' => '/servicos/oleo-filtros-e-mecanica',
                                'texto' => 'Mudança de óleo com todos os filtros (óleo, combustível, ar e habitáculo) e mecânica geral de reparação.'],
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
                        'proporcao' => '50',
                        'py' => '70px',
                        'juntar_abaixo' => true,
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
                    ['type' => 'oficinas_titulo', 'data' => [
                        'titulo' => '4 oficinas - o mesmo cuidado',
                        'botao_texto' => 'Marcar na Oficina mais próxima',
                        'botao_link' => '/marcacoes',
                    ]],
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
                        'proporcao' => '50',
                        'py' => '70px',
                    ]],
                    ['type' => 'servicos_detalhe', 'data' => [
                        'itens' => [
                            ['numero' => '01.', 'etiqueta' => 'manutenção', 'variante' => 'branco', 'titulo' => 'Revisão Oficial', 'imagem' => 'images/servico-revisao.jpg', 'link' => '/servicos/revisao-oficial',
                                'texto' => 'Já é possível fazer as revisões oficiais em oficina multimarca, mantendo a garantia do fabricante — com registo completo da intervenção.',
                                'bullets' => [['texto' => 'Plano de revisão do fabricante'], ['texto' => 'Peças de qualidade equivalente'], ['texto' => 'Check-up gratuito incluído']]],
                            ['numero' => '02.', 'etiqueta' => 'comodidade', 'variante' => 'branco', 'titulo' => 'Inspeção', 'imagem' => 'images/servico-inspecao.jpg', 'link' => '/servicos/inspecao',
                                'texto' => 'A pensar na sua comodidade, acompanhamos o seu carro ao centro de inspeção — deixa o carro connosco e nós tratamos de tudo.',
                                'bullets' => [['texto' => 'Acompanhamento ao centro'], ['texto' => 'Pré-inspeção incluída'], ['texto' => 'Resolução de anomalias no próprio dia']]],
                            ['numero' => '03.', 'etiqueta' => 'segurança', 'variante' => 'branco', 'titulo' => 'Pneus', 'imagem' => 'images/servico-pneus.jpg', 'link' => '/servicos/pneus',
                                'texto' => 'Pneus das melhores marcas ao melhor preço, com alinhamento de direção e equilibragem incluídos.',
                                'bullets' => [['texto' => 'Todas as marcas e medidas'], ['texto' => 'Alinhamento e equilibragem'], ['texto' => 'Verificação gratuita de desgaste']]],
                            ['numero' => '04.', 'etiqueta' => 'carroçaria', 'variante' => 'branco', 'titulo' => 'Colisão e Pintura', 'imagem' => 'images/servico-colisao.jpg', 'link' => '/servicos/colisao-e-pintura',
                                'texto' => 'O nosso moderno Centro de Colisão e Pintura repara todo o tipo de danos de carroçaria — deixou de haver justificação para andar com mossas e riscos.',
                                'bullets' => [['texto' => 'Orçamento gratuito'], ['texto' => 'Gestão do processo com seguradoras'], ['texto' => 'Pintura com acabamento de fábrica']]],
                            ['numero' => '05.', 'etiqueta' => 'conforto', 'variante' => 'branco', 'titulo' => 'Climatização', 'imagem' => 'images/servico-climatizacao.jpg', 'link' => '/servicos/climatizacao',
                                'texto' => 'Manutenção completa do sistema de climatização e ar condicionado, para viajar com conforto e ar saudável em todas as estações.',
                                'bullets' => [['texto' => 'Carga e verificação de gás'], ['texto' => 'Higienização do habitáculo'], ['texto' => 'Deteção e reparação de fugas']]],
                            ['numero' => '06.', 'etiqueta' => 'manutenção', 'variante' => 'branco', 'titulo' => 'Óleo, Filtros e Mecânica', 'imagem' => 'images/servico-oleo.jpg', 'link' => '/servicos/oleo-filtros-e-mecanica',
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

            'servicos/revisao-oficial' => [
                'title' => 'Revisão Oficial',
                'meta_title' => 'Revisão Oficial — GOCARMAT · Revisão oficial sem perder a garantia',
                'meta_description' => 'Faça a revisão oficial do seu carro numa oficina multimarca GOCARMAT, sem perder a garantia de fábrica — ao abrigo do Regulamento Europeu n.º 461/2010.',
                'content' => [
                    ['type' => 'servico_hero', 'data' => [
                        'breadcrumb_pai' => 'Serviços',
                        'breadcrumb_atual' => 'Revisão Oficial',
                        'titulo' => 'Revisão oficial sem perder a garantia.',
                        'texto' => 'Já é possível fazer as revisões oficiais em oficina multimarca — os construtores são obrigados a fornecer os planos de revisão oficial. Na GOCARMAT mantém a garantia de fábrica, com o rigor exigido pelo fabricante — a um preço muito mais vantajoso.',
                        'imagem' => 'images/servico-revisao.jpg',
                        'botoes' => [
                            ['texto' => 'Marcar Revisão', 'link' => '/marcacoes'],
                            ['texto' => 'Ver outros Serviços', 'link' => '/servicos'],
                        ],
                        'faixa_numero' => '461/2010',
                        'faixa_texto' => 'O Regulamento Europeu n.º 461/2010 garante-lhe o poder de escolher a oficina para a revisão da sua viatura — mesmo durante o período de garantia.',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'titulo' => 'Vantagens da revisão oficial na ',
                        'titulo_destaque' => 'GOCARMAT',
                        'itens' => [
                            ['numero' => '01.', 'titulo' => 'Garantia do fabricante', 'texto' => 'Sabe antecipadamente o valor a pagar, pode assistir a todas as reparações e é aconselhado pelos nossos técnicos.'],
                            ['numero' => '02.', 'titulo' => 'Poupe até 60%', 'texto' => 'Face ao valor habitualmente pago nas oficinas do construtor.'],
                            ['numero' => '03.', 'titulo' => 'Garantia das peças', 'texto' => 'Todas as peças substituídas têm garantia ao abrigo do DL 84/2008 (lei do consumidor).'],
                            ['numero' => '05.', 'titulo' => 'Gabinete de qualidade', 'texto' => 'Apoio ao Cliente dedicado para esclarecer e resolver qualquer dificuldade.'],
                            ['numero' => '06.', 'titulo' => 'Assista à revisão', 'texto' => 'Se quiser, pode acompanhar a revisão da sua viatura na oficina.'],
                            ['numero' => '07.', 'titulo' => 'Transparência', 'texto' => 'Competência e honestidade — sabe sempre o que paga e porquê.'],
                        ],
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Não sabe que revisão a sua viatura precisa?',
                        'texto' => 'Marque online e nós confirmamos o plano de revisão oficial do fabricante para o seu carro.',
                        'icone' => 'car-burst',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Serviço',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                    ]],
                ],
            ],

            'servicos/pneus' => [
                'title' => 'Pneus',
                'meta_title' => 'Pneus — GOCARMAT · O único contacto entre o seu carro e a estrada',
                'meta_description' => 'Escolha, verificação de pressão e desgaste, e rotação de pneus na GOCARMAT — com orçamento gratuito e as melhores marcas ao melhor preço.',
                'content' => [
                    ['type' => 'servico_hero', 'data' => [
                        'breadcrumb_pai' => 'Serviços',
                        'breadcrumb_atual' => 'Pneus',
                        'titulo' => 'O único contacto entre o seu carro e a estrada.',
                        'texto' => 'Os pneus são o único elemento em contacto direto com o piso — a sua condição influencia diretamente a segurança, o consumo e o comportamento do veículo. Ajudamo-lo a escolher, verificar e manter os pneus certos para si.',
                        'imagem' => 'images/servico-pneus.jpg',
                        'botoes' => [
                            ['texto' => 'Marcar Pneus', 'link' => '/marcacoes'],
                            ['texto' => 'Ver outros Serviços', 'link' => '/servicos'],
                        ],
                        'faixa_numero' => '1,6mm',
                        'faixa_texto' => 'É a profundidade mínima legal dos sulcos dos pneus — abaixo deste valor, o pneu deixa de ser seguro e a viatura reprova na inspeção.',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'titulo' => 'Vantagens da manutenção de pneus na ',
                        'titulo_destaque' => 'GOCARMAT',
                        'itens' => [
                            ['numero' => '01.', 'titulo' => 'A escolha certa', 'texto' => 'Escolhemos consigo o pneu adequado ao seu estilo de condução e ao piso, com base na etiqueta europeia (eficiência energética, aderência em piso húmido e ruído).'],
                            ['numero' => '02.', 'titulo' => 'Profundidade do piso', 'texto' => 'Verificamos regularmente a profundidade dos sulcos, garantindo o mínimo legal de 1,6 mm em todos os pneus.'],
                            ['numero' => '03.', 'titulo' => 'Pressão correta', 'texto' => 'A verificação mensal da pressão evita desgaste prematuro, maior consumo de combustível e perda de estabilidade.'],
                            ['numero' => '04.', 'titulo' => 'Deteção de desgaste', 'texto' => 'Identificamos desgaste irregular, cortes ou danos, substituindo a tempo qualquer pneu comprometido.'],
                            ['numero' => '05.', 'titulo' => 'Rotação periódica', 'texto' => 'Recomendamos a rotação dos pneus a cada 10.000–12.000 km, para um desgaste uniforme e maior durabilidade.'],
                            ['numero' => '06.', 'titulo' => 'Orçamento gratuito', 'texto' => 'Avaliação e orçamento sem compromisso, com as melhores marcas ao melhor preço.'],
                        ],
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Precisa de trocar os pneus?',
                        'texto' => 'Marque online e ajudamo-lo a escolher os pneus certos para o seu carro.',
                        'icone' => 'car-burst',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Serviço',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                    ]],
                ],
            ],

            'servicos/colisao-e-pintura' => [
                'title' => 'Colisão e Pintura',
                'meta_title' => 'Colisão e Pintura — GOCARMAT · Reparação com acabamento de fábrica',
                'meta_description' => 'Centro de Colisão e Pintura GOCARMAT: desempeno, reparação de amolgadelas e plásticos, e pintura com acabamento de fábrica — com gestão do processo junto da seguradora.',
                'content' => [
                    ['type' => 'servico_hero', 'data' => [
                        'breadcrumb_pai' => 'Serviços',
                        'breadcrumb_atual' => 'Colisão e Pintura',
                        'titulo' => 'Reparação de colisão sem comprometer a qualidade.',
                        'texto' => 'O nosso Centro de Colisão e Pintura repara todo o tipo de danos de carroçaria, respeitando os critérios exigidos pelos fabricantes e pelas seguradoras — com acabamento de fábrica.',
                        'imagem' => 'images/servico-colisao.jpg',
                        'botoes' => [
                            ['texto' => 'Marcar Avaliação', 'link' => '/marcacoes'],
                            ['texto' => 'Ver outros Serviços', 'link' => '/servicos'],
                        ],
                        'faixa_numero' => '100%',
                        'faixa_texto' => 'Reparações feitas de acordo com os critérios e padrões exigidos pelos fabricantes automóveis e pelas seguradoras.',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'titulo' => 'Vantagens do centro de colisão e pintura na ',
                        'titulo_destaque' => 'GOCARMAT',
                        'itens' => [
                            ['numero' => '01.', 'titulo' => 'Desempeno em banco', 'texto' => 'Corrigimos o chassis com equipamento de precisão, devolvendo a estrutura original do veículo.'],
                            ['numero' => '02.', 'titulo' => 'Reparação de amolgadelas', 'texto' => 'Removemos pequenas amolgadelas e mossas sem necessidade de repintura, sempre que possível.'],
                            ['numero' => '03.', 'titulo' => 'Reparação de plásticos', 'texto' => 'Reparamos para-choques e componentes plásticos danificados, evitando substituições dispendiosas.'],
                            ['numero' => '04.', 'titulo' => 'Pintura de acabamento', 'texto' => 'Pintura geral ou parcial com acabamento de fábrica, incluindo polimento e envernizamento de faróis.'],
                            ['numero' => '05.', 'titulo' => 'Gestão com seguradoras', 'texto' => 'Tratamos de todo o processo com a sua seguradora, para que não tenha de se preocupar com nada.'],
                            ['numero' => '06.', 'titulo' => 'Orçamento presencial', 'texto' => 'Orçamento sem compromisso, feito após avaliação da viatura na nossa oficina de Carnaxide.'],
                        ],
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Teve um acidente?',
                        'texto' => 'Marque uma avaliação presencial e cuidamos de todo o processo, incluindo com a sua seguradora.',
                        'icone' => 'car-burst',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Avaliação',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                    ]],
                ],
            ],

            'servicos/inspecao' => [
                'title' => 'Inspeção',
                'meta_title' => 'Inspeção Automóvel — GOCARMAT · Sem surpresas no centro de inspeção',
                'meta_description' => 'Acompanhamos o seu carro ao centro de inspeção periódica, com check-up prévio e resolução de anomalias no próprio dia, sempre que possível.',
                'content' => [
                    ['type' => 'servico_hero', 'data' => [
                        'breadcrumb_pai' => 'Serviços',
                        'breadcrumb_atual' => 'Inspeção',
                        'titulo' => 'Preparamos o seu carro para a inspeção.',
                        'texto' => 'Acompanhamos o seu carro ao centro de inspeção periódica obrigatória, com um check-up prévio que reduz o risco de reprovação — deixa o carro connosco e nós tratamos de tudo.',
                        'imagem' => 'images/servico-inspecao.jpg',
                        'botoes' => [
                            ['texto' => 'Marcar Check-up', 'link' => '/marcacoes'],
                            ['texto' => 'Ver outros Serviços', 'link' => '/servicos'],
                        ],
                        'faixa_numero' => '5',
                        'faixa_texto' => 'É o número máximo de deficiências Tipo 1 toleradas — mais do que isso, ou qualquer deficiência Tipo 2 ou 3, reprova o veículo na inspeção.',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'titulo' => 'Vantagens do check-up de inspeção na ',
                        'titulo_destaque' => 'GOCARMAT',
                        'itens' => [
                            ['numero' => '01.', 'titulo' => 'Check-up prévio', 'texto' => 'Verificamos óleo e filtros, travões, luzes, escovas, fluídos, pneus e equipamento de segurança antes da inspeção.'],
                            ['numero' => '02.', 'titulo' => 'Diagnóstico digital', 'texto' => 'Diagnóstico com registo fotográfico e em vídeo, para saber exatamente o estado da sua viatura.'],
                            ['numero' => '03.', 'titulo' => 'Acompanhamento ao centro', 'texto' => 'Levamos o seu carro ao centro de inspeção, sem se preocupar com deslocações.'],
                            ['numero' => '04.', 'titulo' => 'Resolução no próprio dia', 'texto' => 'Sempre que possível, resolvemos qualquer anomalia detetada no mesmo dia, antes da inspeção.'],
                            ['numero' => '05.', 'titulo' => 'Prazos e imobilizações', 'texto' => 'Alertamos para os prazos — deficiências Tipo 2 dão 30 dias para resolver, Tipo 3 imobilizam o veículo.'],
                            ['numero' => '06.', 'titulo' => 'Motivos de reprovação', 'texto' => 'Corrigimos as causas mais comuns de reprovação: luzes, bateria, direção, chapa de matrícula, pneus e mais.'],
                        ],
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Falta pouco para a inspeção?',
                        'texto' => 'Marque o check-up prévio e evite surpresas no centro de inspeção.',
                        'icone' => 'car-burst',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Check-up',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                    ]],
                ],
            ],

            'servicos/oleo-filtros-e-mecanica' => [
                'title' => 'Óleo, Filtros e Mecânica',
                'meta_title' => 'Óleo, Filtros e Mecânica — GOCARMAT · Óleo e filtros, sempre a par',
                'meta_description' => 'Mudança de óleo e todos os filtros em conjunto, com marcas homologadas pelo fabricante — inclui a Revisão Oficial PLUS com desconto na GOCARMAT.',
                'content' => [
                    ['type' => 'servico_hero', 'data' => [
                        'breadcrumb_pai' => 'Serviços',
                        'breadcrumb_atual' => 'Óleo, Filtros e Mecânica',
                        'titulo' => 'Óleo e filtros, sempre a par.',
                        'texto' => 'Substituímos sempre o óleo e todos os filtros em conjunto — óleo, combustível, ar e habitáculo — com marcas homologadas pelo fabricante, para o motor durar mais e consumir menos.',
                        'imagem' => 'images/servico-oleo.jpg',
                        'botoes' => [
                            ['texto' => 'Marcar Serviço', 'link' => '/marcacoes'],
                            ['texto' => 'Ver outros Serviços', 'link' => '/servicos'],
                        ],
                        'faixa_numero' => '€199',
                        'faixa_texto' => 'É o preço da Revisão Oficial PLUS — revisão oficial com troca completa de filtros, com desconto sobre o preço normal de €385.',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'titulo' => 'Vantagens da mudança de óleo e filtros na ',
                        'titulo_destaque' => 'GOCARMAT',
                        'itens' => [
                            ['numero' => '01.', 'titulo' => 'Óleo e filtro juntos', 'texto' => 'O óleo usado deixa partículas no filtro antigo — trocamos sempre os dois em conjunto, evitando desgaste desnecessário do motor.'],
                            ['numero' => '02.', 'titulo' => 'Mais vida ao motor', 'texto' => 'A lubrificação correta e simultânea de óleo e filtro prolonga significativamente a vida do motor.'],
                            ['numero' => '03.', 'titulo' => 'Remoção de impurezas', 'texto' => 'Eliminamos contaminantes e partículas metálicas que danificam o motor ao longo do tempo.'],
                            ['numero' => '04.', 'titulo' => 'Marcas homologadas', 'texto' => 'Usamos sempre óleos e filtros das marcas recomendadas pelo fabricante do seu carro.'],
                            ['numero' => '05.', 'titulo' => 'Todos os filtros', 'texto' => 'Substituímos também o filtro de combustível, de ar e de habitáculo, sempre que necessário.'],
                            ['numero' => '06.', 'titulo' => 'Revisão Oficial PLUS', 'texto' => 'Combine a revisão oficial com a troca completa de filtros por um preço promocional.'],
                        ],
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Já sabe quando mudou o óleo?',
                        'texto' => 'Marque online a mudança de óleo e filtros — rápido, e com as marcas certas para o seu carro.',
                        'icone' => 'car-burst',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Serviço',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                    ]],
                ],
            ],

            'servicos/climatizacao' => [
                'title' => 'Climatização',
                'meta_title' => 'Climatização — GOCARMAT · Respire melhor dentro do seu carro',
                'meta_description' => 'Higienização, filtro de habitáculo e verificação do sistema de ar condicionado — para um ar mais saudável dentro do seu carro.',
                'content' => [
                    ['type' => 'servico_hero', 'data' => [
                        'breadcrumb_pai' => 'Serviços',
                        'breadcrumb_atual' => 'Climatização',
                        'titulo' => 'Respire melhor dentro do seu carro.',
                        'texto' => 'O ar dentro do habitáculo pode conter 10 vezes mais impurezas do que o ar respirável fora do veículo. Cuidamos do sistema de climatização e do filtro de habitáculo, para viajar com mais conforto e ar mais saudável.',
                        'imagem' => 'images/servico-climatizacao.jpg',
                        'botoes' => [
                            ['texto' => 'Marcar Serviço', 'link' => '/marcacoes'],
                            ['texto' => 'Ver outros Serviços', 'link' => '/servicos'],
                        ],
                        'faixa_numero' => '10x',
                        'faixa_texto' => 'É quantas vezes mais impurezas o ar dentro do habitáculo pode ter, comparado com o ar respirável fora do veículo.',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'titulo' => 'Vantagens da manutenção de climatização na ',
                        'titulo_destaque' => 'GOCARMAT',
                        'itens' => [
                            ['numero' => '01.', 'titulo' => 'Higienização regular', 'texto' => 'Remove impurezas e agentes nocivos — vírus, bactérias e fungos — do habitáculo e das condutas de ar condicionado.'],
                            ['numero' => '02.', 'titulo' => 'Filtro de habitáculo', 'texto' => 'Substituímos o filtro a cada 15.000 km ou uma vez por ano, mesmo que pareça limpo à vista.'],
                            ['numero' => '03.', 'titulo' => 'Verificação do sistema', 'texto' => 'Verificamos o sistema de ar condicionado a cada 20.000 km, prolongando a vida dos componentes.'],
                            ['numero' => '04.', 'titulo' => 'Proteção em zonas urbanas', 'texto' => 'Especialmente importante em ambientes urbanos, onde contaminantes invisíveis se acumulam sem sinais visíveis no filtro.'],
                            ['numero' => '05.', 'titulo' => 'Deteção de fugas', 'texto' => 'Verificamos e corrigimos fugas de gás, mantendo o sistema eficiente.'],
                            ['numero' => '06.', 'titulo' => 'Inspeção de condutas', 'texto' => 'Recomendada para viaturas mais antigas sem filtro de habitáculo, garantindo a higiene do circuito de ar.'],
                        ],
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Quando foi a última higienização?',
                        'texto' => 'Marque online a manutenção do sistema de climatização e respire um ar mais saudável.',
                        'icone' => 'car-burst',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Serviço',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
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
                        'imagem' => 'images/servico-revisao.jpg',
                        'lado_titulo' => 'Rede de Oficinas Multimarca na Grande Lisboa',
                        'lado_subtitulo' => 'GOCARMAT® – Serviço de Apoio ao Cliente/360.',
                        'lado_texto' => 'Sempre que sente necessidade de consultar uma oficina, o nosso serviço de Apoio ao Cliente está à sua espera. Responde a dúvidas e preocupações que possam surgir no dia a dia do seu automóvel, ajudando na sua manutenção e assistência técnica. Seja na marcação de uma oficina Gocarmat ou na verificação de quais os pneus recomendados para a sua viatura, conte com o Suporte e Apoio ao Cliente da Gocarmat.',
                        'lado_texto_destaque' => 'Serviço de Apoio ao Cliente Gocarmat, existimos para o(a) ajudar na decisão da assistência e manutenção do seu automóvel.',
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

            'campanhas' => [
                'title' => 'Campanhas',
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
                'meta_title' => 'Revisão Oficial por 94,90€ — Campanha GOCARMAT',
                'meta_description' => 'Revisão oficial com óleo, filtro de óleo, filtro de ar e check-up completo, por apenas 94,90€ — mão de obra incluída.',
                'content' => [
                    ['type' => 'campanha_cta_fixo', 'data' => [
                        'titulo' => 'Pronto para marcar?',
                        'texto' => 'Marque já a sua revisão oficial com este preço especial.',
                        'icone' => 'car-burst',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Agora',
                        'botao_link' => '/marcacoes',
                    ]],
                    ['type' => 'servico_hero', 'data' => [
                        'breadcrumb_pai' => 'Campanhas',
                        'breadcrumb_atual' => 'Revisão Oficial',
                        'titulo' => 'Revisão oficial por apenas 94,90€.',
                        'texto' => 'Substituição do óleo do motor, filtro de óleo e filtro de ar, com check-up completo à viatura — mão de obra incluída.',
                        'imagem' => 'images/campanha-revisao-oficial.jpg',
                        'botoes' => [
                            ['texto' => 'Marcar Agora', 'link' => '/marcacoes'],
                            ['texto' => 'Ver outras Campanhas', 'link' => '/campanhas'],
                        ],
                        'faixa_numero' => '94,90€',
                        'faixa_texto' => 'Preço fixo, com mão de obra incluída — válido para a generalidade das marcas.',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'titulo' => 'O que está incluído nesta ',
                        'titulo_destaque' => 'campanha',
                        'itens' => [
                            ['numero' => '01.', 'titulo' => 'Óleo do motor', 'texto' => 'Substituição do óleo do motor (5W30 / 5W40 / 10W40, até 5 litros).'],
                            ['numero' => '02.', 'titulo' => 'Filtro de óleo', 'texto' => 'Substituição do filtro de óleo.'],
                            ['numero' => '03.', 'titulo' => 'Filtro de ar', 'texto' => 'Substituição do filtro de ar.'],
                            ['numero' => '04.', 'titulo' => 'Check-up completo', 'texto' => 'Verificação do sistema de travagem, arranque e carga, iluminação, direção e suspensão.'],
                            ['numero' => '05.', 'titulo' => 'Diagnóstico digital', 'texto' => 'Diagnóstico EOBD digital, para viaturas a partir de 2010.'],
                            ['numero' => '06.', 'titulo' => 'Mão de obra incluída', 'texto' => 'Sem custos adicionais de mão de obra no serviço realizado.'],
                        ],
                    ]],
                    ['type' => 'campanha_checkup', 'data' => [
                        'titulo' => 'Todos os serviços GOCARMAT em promoção incluem um check-up à sua viatura',
                        'texto' => 'O check-up engloba a verificação de:',
                        'imagem' => 'images/campanha-revisao-oficial.jpg',
                        'itens' => [
                            ['texto' => 'Sistema de travagem (discos, pastilhas, calços, maxilas e líquido dos travões)'],
                            ['texto' => 'Sistema de arranque e carga (bateria, alternador e motor de arranque)'],
                            ['texto' => 'Sistema de iluminação, lâmpadas e óticas, piscas e iluminação da matrícula'],
                            ['texto' => 'Sistema de direção e suspensão/amortecedores'],
                            ['texto' => 'Diagnóstico digital EOBD (conjunto de testes imprescindíveis para a deteção de avarias na sua viatura, para veículos a partir de 2010)'],
                        ],
                    ]],
                    ['type' => 'texto', 'data' => [
                        'titulo' => 'Condições',
                        'corpo' => '<p>Promoção válida para a generalidade das marcas, com mão de obra incluída.<br>'
                            .'As promoções indicadas não são acumuláveis com outras campanhas ou promoções em vigor, salvo quando previamente autorizado pela GOCARMAT.<br>'
                            .'A promoção não é válida para as viaturas Ferrari, Maserati, Lamborghini, Porsche, Aston Martin, Jaguar, Bentley, Lexus, BMW série 5 e 7, Audi A5, A6, A7 e A8, Mercedes Classe E/S/5/6/7, monovolumes e jipes. Monovolumes, autocaravanas e jipes poderão ser incluídos mediante um suplemento pago diretamente no local — informe-se deste valor, pois pode haver oscilações de acordo com a marca e modelo da sua viatura.<br>'
                            .'A GOCARMAT reserva-se no direito de incluir ou excluir qualquer viatura não mencionada acima. Consulte a oficina para mais detalhes.</p>',
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Pronto para marcar?',
                        'texto' => 'Marque já a sua revisão oficial com este preço especial.',
                        'icone' => 'car-burst',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Agora',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                        'ligar_barra_fixa' => true,
                    ]],
                ],
            ],

            'campanhas/pastilhas-travao' => [
                'title' => 'Campanha — Pastilhas de Travão',
                'meta_title' => 'Pastilhas de Travão por 84,90€ — Campanha GOCARMAT',
                'meta_description' => 'Substituição de um jogo de pastilhas de travão, com check-up incluído, por apenas 84,90€.',
                'content' => [
                    ['type' => 'campanha_cta_fixo', 'data' => [
                        'titulo' => 'Precisa de trocar as pastilhas?',
                        'texto' => 'Marque já a substituição com este preço especial.',
                        'icone' => 'car-burst',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Agora',
                        'botao_link' => '/marcacoes',
                    ]],
                    ['type' => 'servico_hero', 'data' => [
                        'breadcrumb_pai' => 'Campanhas',
                        'breadcrumb_atual' => 'Pastilhas de Travão',
                        'titulo' => 'Pastilhas de travão por apenas 84,90€.',
                        'texto' => 'Substituição de um jogo de pastilhas de travão (eixo dianteiro ou traseiro), com check-up incluído.',
                        'imagem' => 'images/campanha-pastilhas-travao.jpg',
                        'botoes' => [
                            ['texto' => 'Marcar Agora', 'link' => '/marcacoes'],
                            ['texto' => 'Ver outras Campanhas', 'link' => '/campanhas'],
                        ],
                        'faixa_numero' => '84,90€',
                        'faixa_texto' => 'Preço fixo por um jogo de pastilhas de travão, com check-up incluído.',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'titulo' => 'O que está incluído nesta ',
                        'titulo_destaque' => 'campanha',
                        'itens' => [
                            ['numero' => '01.', 'titulo' => 'Pastilhas de travão', 'texto' => 'Substituição de um jogo de pastilhas de travão, no eixo dianteiro ou traseiro.'],
                            ['numero' => '02.', 'titulo' => 'Inspeção do disco', 'texto' => 'Avaliação do estado dos discos de travão associados.'],
                            ['numero' => '03.', 'titulo' => 'Verificação do fluído', 'texto' => 'Verificação do nível e estado do fluído de travões.'],
                            ['numero' => '04.', 'titulo' => 'Check-up geral', 'texto' => 'Verificação do sistema de arranque e carga, iluminação, direção e suspensão.'],
                            ['numero' => '05.', 'titulo' => 'Diagnóstico digital', 'texto' => 'Diagnóstico EOBD digital, para viaturas a partir de 2010.'],
                            ['numero' => '06.', 'titulo' => 'Sem sensores de desgaste', 'texto' => 'O preço não inclui sensores de desgaste, quando aplicável à sua viatura.'],
                        ],
                    ]],
                    ['type' => 'campanha_checkup', 'data' => [
                        'titulo' => 'Todos os serviços GOCARMAT em promoção incluem um check-up à sua viatura',
                        'texto' => 'O check-up engloba a verificação de:',
                        'imagem' => 'images/campanha-pastilhas-travao.jpg',
                        'itens' => [
                            ['texto' => 'Sistema de travagem (discos, pastilhas, calços, maxilas e líquido dos travões)'],
                            ['texto' => 'Sistema de arranque e carga (bateria, alternador e motor de arranque)'],
                            ['texto' => 'Sistema de iluminação, lâmpadas e óticas, piscas e iluminação da matrícula'],
                            ['texto' => 'Sistema de direção e suspensão/amortecedores'],
                            ['texto' => 'Diagnóstico digital EOBD (conjunto de testes imprescindíveis para a deteção de avarias na sua viatura, para veículos a partir de 2010)'],
                        ],
                    ]],
                    ['type' => 'texto', 'data' => [
                        'titulo' => 'Condições',
                        'corpo' => '<p>Preço fixo por um jogo de pastilhas de travão (eixo dianteiro ou traseiro), com check-up incluído.<br>'
                            .'Esta campanha não inclui os avisadores de desgaste das pastilhas.<br>'
                            .'As promoções indicadas não são acumuláveis com outras campanhas ou promoções em vigor, salvo quando previamente autorizado pela GOCARMAT.<br>'
                            .'A promoção não é válida para as viaturas Ferrari, Maserati, Lamborghini, Porsche, Aston Martin, Jaguar, Bentley, Lexus, BMW série 5 e 7, Audi A5, A6, A7 e A8, Mercedes Classe E/S/5/6/7, monovolumes e jipes. Monovolumes, autocaravanas e jipes poderão ser incluídos mediante um suplemento pago diretamente no local — informe-se deste valor, pois pode haver oscilações de acordo com a marca e modelo da sua viatura.<br>'
                            .'A GOCARMAT reserva-se no direito de incluir ou excluir qualquer viatura não mencionada acima. Consulte a oficina para mais detalhes.</p>',
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Precisa de trocar as pastilhas?',
                        'texto' => 'Marque já a substituição com este preço especial.',
                        'icone' => 'car-burst',
                        'cor_icone' => 'energia',
                        'botao_texto' => 'Marcar Agora',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                        'ligar_barra_fixa' => true,
                    ]],
                ],
            ],
        ];
    }
}
