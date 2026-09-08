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
                            ['etiqueta' => 'lab', 'icone' => 'bolt', 'titulo' => 'EVA LAB', 'texto' => 'Diagnóstico e reparação de baterias e eletrónica de alta tensão.', 'link' => '/eva-powerlab/eva-lab'],
                            ['etiqueta' => 'resgate', 'icone' => 'car-burst', 'titulo' => 'RESCUE', 'texto' => 'Desbloqueio de híbridos e elétricos imobilizados.', 'link' => '/eva-powerlab/rescue'],
                            ['etiqueta' => 'segurança', 'icone' => 'car-burst', 'titulo' => 'EVA COLLISION', 'texto' => 'Colisão de veículos eletrificados, com protocolos de alta tensão.', 'link' => '/eva-powerlab/eva-collision'],
                            ['etiqueta' => 'tis', 'icone' => 'bolt', 'titulo' => 'TESLA INDEPENDENT SERVICE', 'texto' => 'Serviço independente para Model S, X, 3 e Y.', 'link' => '/eva-powerlab/tesla-independent-service'],
                            ['etiqueta' => 'garantia', 'icone' => 'shield', 'titulo' => 'EVA BATTERY WARRANTY', 'texto' => 'Extensão de garantia até 5 anos para baterias.', 'link' => '/eva-powerlab/battery-warranty'],
                            ['etiqueta' => 'mv-ber', 'icone' => 'certificate', 'titulo' => 'CERTIFICAÇÃO MV-BER', 'texto' => 'Certificado, Livro de Manutenção Digital e selo de qualidade.', 'link' => '/eva-powerlab/certificacao-mv-ber'],
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

            'eva-powerlab/eva-lab' => [
                'title' => 'EVA Lab',
                'meta_title' => 'EVA Lab — Laboratório de Alta Tensão | Gocarmat',
                'meta_description' => 'Diagnóstico de BMS, reparação de placas eletrónicas e teste de módulos e células de baterias de alta tensão. Reparamos em vez de substituir.',
                'content' => [
                    ['type' => 'servico_hero', 'data' => [
                        'fundo' => 'carbono',
                        'breadcrumb_pai' => 'EVA Powerlab',
                        'breadcrumb_link' => '/eva-powerlab',
                        'breadcrumb_atual' => 'EVA Lab',
                        'titulo' => 'Reparar em vez de substituir.',
                        'texto' => 'Laboratório de alta tensão para diagnóstico e recuperação de baterias e eletrónica de veículos elétricos e híbridos.',
                        'imagem' => 'images/servico-revisao.jpg',
                        'botoes' => [
                            ['texto' => 'Enviar um Caso para Análise', 'link' => '/marcacoes'],
                            ['texto' => 'Falar com um Técnico', 'link' => '/marcacoes'],
                        ],
                    ]],
                    ['type' => 'texto', 'data' => [
                        'corpo' => '<p class="text-2xl font-bold leading-[1.35] tracking-[-0.16px] text-carbono sm:text-[28px]">Quando uma bateria de alta tensão dá erro, a resposta habitual da rede oficial é substituir o conjunto completo. É a solução mais rápida — e quase sempre <span class="text-energia">a mais cara</span>.</p>'
                            .'<p>O EVA Lab existe para dar uma segunda opção. É uma unidade laboratorial equipada para caracterizar e reparar sistemas eletrónicos e baterias de alta potência ao nível do módulo, da célula e da placa. Na prática: identificamos o que falhou de facto, e intervimos apenas nisso.</p>',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'compacto' => true,
                        'colunas' => 4,
                        'titulo' => 'O que ',
                        'titulo_destaque' => 'fazemos',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Diagnóstico avançado de BMS', 'texto' => 'Análise do sistema de gestão da bateria para distinguir uma falha real de célula de um erro de leitura, comunicação ou balanceamento — a distinção muda por completo o custo da reparação.'],
                            ['numero' => '02', 'titulo' => 'Reparação de placas eletrónicas', 'texto' => 'Intervenção ao nível do componente em placas de BMS, controlo e potência, em vez da substituição da unidade completa.'],
                            ['numero' => '03', 'titulo' => 'Testes de módulos e células', 'texto' => 'Caracterização individual de módulos e células para localizar a origem da degradação e avaliar se o pack é recuperável.'],
                            ['numero' => '04', 'titulo' => 'Engenharia inversa e validação', 'texto' => 'Para sistemas sem documentação disponível ou fora de suporte do fabricante. Nenhum componente sai do laboratório sem validação funcional.'],
                        ],
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'compacto' => true,
                        'colunas' => 4,
                        'titulo' => 'Porque é que isto ',
                        'titulo_destaque' => 'importa',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Evita substituições desnecessárias', 'texto' => 'Um pack não se substitui por causa de um módulo.'],
                            ['numero' => '02', 'titulo' => 'Recupera componentes críticos', 'texto' => 'Que de outra forma seriam abatidos.'],
                            ['numero' => '03', 'titulo' => 'Reduz o custo de reparação', 'texto' => 'Em sistemas complexos, onde a peça nova é a maior fatia do orçamento.'],
                            ['numero' => '04', 'titulo' => 'Menos desperdício', 'texto' => 'Cada pack recuperado é um pack que não vai a resíduo.'],
                        ],
                    ]],
                    ['type' => 'texto', 'data' => [
                        'titulo' => 'Para quem',
                        'corpo' => '<div class="mt-2 space-y-5">'
                            .'<div class="flex items-start gap-4"><svg class="mt-0.5 size-6 shrink-0 text-energia" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10" cy="10" r="8.2" /><path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" /></svg><span class="text-base leading-[1.5] tracking-[-0.16px] text-carbono">Oficinas e centros de colisão sem capacidade de intervenção em alta tensão</span></div>'
                            .'<div class="flex items-start gap-4"><svg class="mt-0.5 size-6 shrink-0 text-energia" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10" cy="10" r="8.2" /><path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" /></svg><span class="text-base leading-[1.5] tracking-[-0.16px] text-carbono">Gestores de frota com veículos imobilizados por avaria de bateria</span></div>'
                            .'<div class="flex items-start gap-4"><svg class="mt-0.5 size-6 shrink-0 text-energia" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10" cy="10" r="8.2" /><path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" /></svg><span class="text-base leading-[1.5] tracking-[-0.16px] text-carbono">Peritos e seguradoras que precisam de uma avaliação técnica independente antes de decidir uma perda total</span></div>'
                            .'<div class="flex items-start gap-4"><svg class="mt-0.5 size-6 shrink-0 text-energia" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10" cy="10" r="8.2" /><path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" /></svg><span class="text-base leading-[1.5] tracking-[-0.16px] text-carbono">Proprietários com um orçamento de substituição de bateria em mãos e vontade de ouvir uma segunda opinião</span></div>'
                            .'</div>',
                    ]],
                    ['type' => 'porque_faq', 'data' => [
                        'titulo' => 'Porquê o EVA Lab?',
                        'texto' => 'Reparamos ao nível do módulo, da célula e da placa — identificamos o que falhou de facto, e intervimos apenas nisso. Nenhum componente sai do laboratório sem validação funcional.',
                        'imagem' => 'images/eva-car.png',
                        'faq_titulo' => 'Perguntas frequentes',
                        'faqs' => [
                            ['pergunta' => 'E se o pack não for recuperável?', 'resposta' => 'Dizemo-lo antes de avançar, com o relatório de diagnóstico e a fundamentação técnica. Não iniciamos reparações sem viabilidade confirmada.'],
                            ['pergunta' => 'Fica com garantia?', 'resposta' => 'Sim. As baterias reparadas no EVA Lab ficam cobertas pelas condições do EVA Battery Warranty.'],
                        ],
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Tem um pack com erro e um orçamento de substituição em cima da mesa?',
                        'texto' => 'Envie-nos os dados do veículo e o código de avaria. Dizemos-lhe se vale a pena reparar.',
                        'icone' => 'bolt',
                        'cor_icone' => 'lima',
                        'botao_texto' => 'Enviar Caso',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                    ]],
                ],
            ],

            'eva-powerlab/rescue' => [
                'title' => 'Rescue',
                'meta_title' => 'Rescue — Desbloqueio de Híbridos e Elétricos | Gocarmat',
                'meta_description' => 'Veículo híbrido que não entra em modo READY? Diagnóstico no local, reativação funcional e preparação segura para transporte, sem reboque desnecessário.',
                'content' => [
                    ['type' => 'servico_hero', 'data' => [
                        'fundo' => 'carbono',
                        'breadcrumb_pai' => 'EVA Powerlab',
                        'breadcrumb_link' => '/eva-powerlab',
                        'breadcrumb_atual' => 'Rescue',
                        'titulo' => 'O carro não arranca. Nem sempre precisa de reboque.',
                        'texto' => 'Serviço técnico para híbridos e elétricos imobilizados por bloqueio eletrónico, bateria de alta tensão em fim de carga ou falha de comunicação entre sistemas.',
                        'imagem' => 'images/servico-inspecao.jpg',
                        'botoes' => [
                            ['texto' => 'Pedir Assistência', 'link' => '/marcacoes'],
                            ['texto' => 'Como Funciona', 'link' => '/marcacoes'],
                        ],
                    ]],
                    ['type' => 'texto', 'data' => [
                        'corpo' => '<p class="text-2xl font-bold leading-[1.35] tracking-[-0.16px] text-carbono sm:text-[28px]">Um híbrido que fica sem combustível não se resolve com cinco litros no depósito. Um elétrico com a bateria de alta tensão abaixo do limite mínimo não se resolve <span class="text-energia">com cabos de arranque</span>.</p>'
                            .'<p>Nestes casos, o veículo entra em bloqueio e deixa de entrar em modo READY — e o reboque para a marca é, muitas vezes, o primeiro reflexo. Nem sempre é necessário. O serviço Rescue faz o diagnóstico no local e, quando é possível, a reativação funcional ali mesmo.</p>',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'compacto' => true,
                        'colunas' => 4,
                        'titulo' => 'Situações que ',
                        'titulo_destaque' => 'resolvemos',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Sem modo READY', 'texto' => 'Veículos que não entram em modo de condução.'],
                            ['numero' => '02', 'titulo' => 'Bloqueios eletrónicos', 'texto' => 'Falhas de comunicação entre sistemas.'],
                            ['numero' => '03', 'titulo' => 'Bateria AT no limite', 'texto' => 'Bateria de alta tensão abaixo do limite mínimo.'],
                            ['numero' => '04', 'titulo' => 'Combustível no limite', 'texto' => 'Em veículos híbridos.'],
                        ],
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'compacto' => true,
                        'colunas' => 3,
                        'titulo' => 'O que a intervenção ',
                        'titulo_destaque' => 'inclui',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Diagnóstico no local', 'texto' => 'Avaliação da causa da imobilização antes de qualquer decisão de transporte.'],
                            ['numero' => '02', 'titulo' => 'Reativação funcional', 'texto' => 'Quando o quadro técnico o permite, o veículo sai pelo seu próprio pé.'],
                            ['numero' => '03', 'titulo' => 'Preparação segura para transporte', 'texto' => 'Quando não permite, o veículo é preparado e imobilizado em segurança — com os procedimentos de alta tensão que um reboque convencional não executa.'],
                        ],
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'compacto' => true,
                        'colunas' => 3,
                        'titulo' => 'O que ',
                        'titulo_destaque' => 'ganha com isto',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Menos tempo parado', 'texto' => 'A diferença entre resolver no local e esperar por uma marcação na rede oficial mede-se em dias.'],
                            ['numero' => '02', 'titulo' => 'Menos reboques desnecessários', 'texto' => 'E menos um custo na fatura.'],
                            ['numero' => '03', 'titulo' => 'Menos degradação da bateria', 'texto' => 'Um pack que fica longos períodos com o estado de carga (SoC) muito baixo degrada-se quimicamente — resolver depressa protege o ativo mais caro do veículo.'],
                        ],
                    ]],
                    ['type' => 'porque_faq', 'data' => [
                        'titulo' => 'Porquê o Rescue?',
                        'texto' => 'Nem todo o veículo imobilizado precisa de reboque. Fazemos diagnóstico no local e, sempre que possível, reativação funcional imediata — para o carro seguir viagem pelo seu próprio pé.',
                        'imagem' => 'images/eva-car.png',
                        'faq_titulo' => 'Perguntas frequentes',
                        'faqs' => [
                            ['pergunta' => 'O Rescue substitui sempre o reboque?', 'resposta' => 'Não. Sempre que o quadro técnico o permite, reativamos o veículo no local. Quando não permite, preparamos e imobilizamos o veículo em segurança para transporte — com os procedimentos de alta tensão que um reboque convencional não executa.'],
                            ['pergunta' => 'Porque é que a rapidez importa tanto?', 'resposta' => 'Porque um pack que fica muito tempo com o estado de carga (SoC) muito baixo degrada-se quimicamente. Resolver depressa protege o ativo mais caro do veículo.'],
                        ],
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Veículo imobilizado neste momento?',
                        'texto' => 'Descreva o que aparece no painel e dizemos-lhe o que fazer a seguir.',
                        'icone' => 'car-burst',
                        'cor_icone' => 'lima',
                        'botao_texto' => 'Pedir Assistência',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                    ]],
                ],
            ],

            'eva-powerlab/tesla-independent-service' => [
                'title' => 'Tesla Independent Service',
                'meta_title' => 'Serviço Independente para Tesla | Gocarmat TIS',
                'meta_description' => 'Diagnóstico, eletrónica, alta tensão e carregamento para Tesla Model S, X, 3 e Y. Oficina independente, sem afiliação à Tesla, Inc.',
                'content' => [
                    ['type' => 'servico_hero', 'data' => [
                        'fundo' => 'carbono',
                        'breadcrumb_pai' => 'EVA Powerlab',
                        'breadcrumb_link' => '/eva-powerlab',
                        'breadcrumb_atual' => 'Tesla Independent Service',
                        'titulo' => 'Serviço independente para veículos Tesla.',
                        'texto' => 'Diagnóstico, eletrónica, alta tensão e sistemas de carregamento para Model S, Model X, Model 3 e Model Y — fora da rede oficial.',
                        'imagem' => 'images/eva-bg.jpg',
                        'botoes' => [
                            ['texto' => 'Pedir Orçamento', 'link' => '/marcacoes'],
                            ['texto' => 'Ver Capacidades Técnicas', 'link' => '/marcacoes'],
                        ],
                    ]],
                    ['type' => 'texto', 'data' => [
                        'corpo' => '<p class="text-2xl font-bold leading-[1.35] tracking-[-0.16px] text-carbono sm:text-[28px]">Ter um Tesla fora de garantia não deveria significar depender de <span class="text-energia">um único ponto de serviço</span>.</p>'
                            .'<p>O direito europeu à reparação independente existe precisamente para isso — e a Gocarmat opera dentro dele. A unidade TIS reúne ferramentas de diagnóstico dedicadas e procedimentos técnicos específicos para a plataforma Tesla, com intervenção que vai da leitura de códigos de avaria à reparação de componentes de alta tensão.</p>'
                            .'<p class="text-xs text-carbono/60">A Gocarmat não está afiliada nem é endossada pela Tesla, Inc. Tesla e os respetivos logótipos são marcas registadas da Tesla, Inc. Este é um serviço independente, prestado ao abrigo do quadro regulamentar europeu aplicável à reparação automóvel.</p>',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'compacto' => true,
                        'colunas' => 4,
                        'titulo' => '',
                        'titulo_destaque' => 'Capacidades',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Diagnóstico', 'texto' => 'Ferramentas dedicadas, com diagnóstico completo independentemente da versão de firmware. Leitura de dados em tempo real (Live Data), auto-testes e acesso a bases de dados de avarias.'],
                            ['numero' => '02', 'titulo' => 'Eletrónica e alta tensão', 'texto' => 'Reparação de componentes eletrónicos e de alta tensão, intervenção em baterias e em sistemas de carregamento.'],
                            ['numero' => '03', 'titulo' => 'Configuração e calibração', 'texto' => 'Instalação e configuração de navegação, calibração de radar (AP2.5/AP3.0) e de outros sistemas, emparelhamento e adaptação de módulos, atualização de firmware.'],
                            ['numero' => '04', 'titulo' => 'Reparação pós-colisão', 'texto' => 'Reposição de módulos de segurança após reparação — incluindo BMS, TAS e módulos de airbag — e reconfiguração de módulos eletrónicos substituídos.'],
                        ],
                        'nota' => 'As operações de reposição de módulos de segurança são executadas apenas em veículos reparados na nossa oficina ou com relatório de reparação validado, mediante comprovativo de propriedade.',
                    ]],
                    ['type' => 'texto', 'data' => [
                        'titulo' => 'Modelos abrangidos',
                        'corpo' => '<div class="mt-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">'
                            .'<div class="rounded-2xl border border-carbono/10 bg-white px-6 py-6 text-center"><div class="flex h-32 items-center justify-center"><img src="/images/eva-car-profile.png" alt="Tesla Model S" class="h-full w-auto object-contain"></div><p class="mt-3 font-mono text-sm font-bold uppercase tracking-[-0.16px] text-carbono">Tesla Model S</p></div>'
                            .'<div class="rounded-2xl border border-carbono/10 bg-white px-6 py-6 text-center"><div class="flex h-32 items-center justify-center"><img src="/images/eva-car-profile.png" alt="Tesla Model X" class="h-full w-auto object-contain"></div><p class="mt-3 font-mono text-sm font-bold uppercase tracking-[-0.16px] text-carbono">Tesla Model X</p></div>'
                            .'<div class="rounded-2xl border border-carbono/10 bg-white px-6 py-6 text-center"><div class="flex h-32 items-center justify-center"><img src="/images/eva-car-profile.png" alt="Tesla Model 3" class="h-full w-auto object-contain"></div><p class="mt-3 font-mono text-sm font-bold uppercase tracking-[-0.16px] text-carbono">Tesla Model 3</p></div>'
                            .'<div class="rounded-2xl border border-carbono/10 bg-white px-6 py-6 text-center"><div class="flex h-32 items-center justify-center"><img src="/images/eva-car-profile.png" alt="Tesla Model Y" class="h-full w-auto object-contain"></div><p class="mt-3 font-mono text-sm font-bold uppercase tracking-[-0.16px] text-carbono">Tesla Model Y</p></div>'
                            .'</div>',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'compacto' => true,
                        'colunas' => 4,
                        'titulo' => '',
                        'titulo_destaque' => 'Diferenciação',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Alternativa técnica real', 'texto' => 'À rede oficial, com capacidade de diagnóstico equivalente nas áreas em que intervimos.'],
                            ['numero' => '02', 'titulo' => 'Maior flexibilidade operacional', 'texto' => 'Marcação, prazos e âmbito de intervenção negociados diretamente consigo.'],
                            ['numero' => '03', 'titulo' => 'Custos mais baixos', 'texto' => 'Redução significativa dos custos de reparação, sobretudo em eletrónica, onde a reparação substitui a troca de módulo.'],
                            ['numero' => '04', 'titulo' => 'Menor dependência do fabricante', 'texto' => 'Para operações correntes de manutenção e reparação.'],
                            ['numero' => '05', 'titulo' => 'Resposta mais rápida', 'texto' => 'Tempos de resposta mais curtos.'],
                        ],
                    ]],
                    ['type' => 'porque_faq', 'data' => [
                        'titulo' => 'Porquê o TIS?',
                        'texto' => 'Ter um Tesla fora de garantia não deveria significar depender de um único ponto de serviço. Operamos ao abrigo do direito europeu à reparação independente.',
                        'imagem' => 'images/eva-car-profile.png',
                        'faq_titulo' => 'Perguntas frequentes',
                        'faqs' => [
                            ['pergunta' => 'Este serviço está afiliado à Tesla?', 'resposta' => 'Não. É um serviço independente, sem afiliação nem endosso da Tesla, Inc., prestado ao abrigo do quadro regulamentar europeu aplicável à reparação automóvel.'],
                            ['pergunta' => 'Que modelos estão abrangidos?', 'resposta' => 'Tesla Model S, Model X, Model 3 e Model Y.'],
                        ],
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Descreva-nos o problema e o modelo.',
                        'texto' => 'Damos-lhe uma avaliação técnica e um orçamento antes de o carro entrar na oficina.',
                        'icone' => 'bolt',
                        'cor_icone' => 'lima',
                        'botao_texto' => 'Pedir Orçamento',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                    ]],
                ],
            ],

            'eva-powerlab/eva-collision' => [
                'title' => 'EVA Collision',
                'meta_title' => 'EVA Collision Center — Colisão de Veículos Elétricos | Gocarmat',
                'meta_description' => 'Avaliação de baterias pós-colisão, desativação segura de alta tensão e reparação compatível com a arquitetura elétrica. Menos perdas totais.',
                'content' => [
                    ['type' => 'servico_hero', 'data' => [
                        'fundo' => 'carbono',
                        'breadcrumb_pai' => 'EVA Powerlab',
                        'breadcrumb_link' => '/eva-powerlab',
                        'breadcrumb_atual' => 'EVA Collision',
                        'titulo' => 'Um elétrico sinistrado não é um carro sinistrado com um motor diferente.',
                        'texto' => 'Centro de colisão para veículos elétricos e híbridos, com protocolos de alta tensão em todas as fases — da receção à entrega.',
                        'imagem' => 'images/servico-colisao.jpg',
                        'botoes' => [
                            ['texto' => 'Encaminhar um Sinistro', 'link' => '/marcacoes'],
                            ['texto' => 'Ver Protocolo de Receção', 'link' => '/marcacoes'],
                        ],
                    ]],
                    ['type' => 'texto', 'data' => [
                        'corpo' => '<p class="text-2xl font-bold leading-[1.35] tracking-[-0.16px] text-carbono sm:text-[28px]">Depois de um embate, a bateria de alta tensão é <span class="text-energia">a primeira incógnita e a mais determinante</span>.</p>'
                            .'<p>Se ninguém a avaliar tecnicamente, restam duas saídas más: reparar sem saber o que se passa lá dentro, ou abater o veículo por precaução. O EVA Collision Center resolve a incógnita. Avaliamos o estado real da bateria e dos sistemas de alta tensão antes de qualquer decisão de reparação — e reparamos com procedimentos compatíveis com a arquitetura elétrica do veículo.</p>',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'compacto' => true,
                        'colunas' => 4,
                        'titulo' => 'Protocolo de receção de ',
                        'titulo_destaque' => 'veículo sinistrado',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Verificação da bateria', 'texto' => 'Avaliação de deformação, perfuração e comprometimento do invólucro.'],
                            ['numero' => '02', 'titulo' => 'Leitura térmica', 'texto' => 'Registo de picos de temperatura por infravermelhos — o primeiro indicador de um evento térmico em curso.'],
                            ['numero' => '03', 'titulo' => 'Deteção de fugas', 'texto' => 'De gases associados ao início de um evento térmico.'],
                            ['numero' => '04', 'titulo' => 'Registo antifraude', 'texto' => 'Cada leitura fica documentada e rastreável em sistema informático.'],
                        ],
                        'nota' => 'Este registo serve dois fins: proteger quem trabalha no veículo e dar ao perito e à seguradora uma base documental que sustente a decisão, seja ela reparar ou abater.',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'compacto' => true,
                        'colunas' => 4,
                        'titulo' => 'Capacidades ',
                        'titulo_destaque' => 'técnicas',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Avaliação de danos HV', 'texto' => 'Em baterias e componentes de alta tensão pós-colisão.'],
                            ['numero' => '02', 'titulo' => 'Desativação segura', 'texto' => 'Protocolos de desativação segura de sistemas de alta tensão.'],
                            ['numero' => '03', 'titulo' => 'Carroçaria compatível', 'texto' => 'Pontos de fixação, zonas de deformação programada e trajetos de cablagem de alta tensão condicionam a reparação de forma que uma oficina generalista não tem obrigação de conhecer.'],
                            ['numero' => '04', 'titulo' => 'Imobilização segura', 'texto' => 'De acordo com as normas europeias aplicáveis.'],
                        ],
                    ]],
                    ['type' => 'texto', 'data' => [
                        'titulo' => 'Segurança e imobilização de veículos eletrificados',
                        'corpo' => '<p>Serviço autónomo, disponível também fora de contexto de colisão — em avaria ou em situação de risco elétrico.</p>'
                            .'<div class="mt-8 grid gap-8 sm:grid-cols-2">'
                            .'<div><p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-energia">Inclui</p>'
                            .'<div class="mt-4 space-y-4">'
                            .'<div class="flex items-start gap-4"><svg class="mt-0.5 size-6 shrink-0 text-energia" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10" cy="10" r="8.2" /><path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" /></svg><span class="text-base leading-[1.5] tracking-[-0.16px] text-carbono">Desativação de sistemas de alta tensão (High Voltage Disable)</span></div>'
                            .'<div class="flex items-start gap-4"><svg class="mt-0.5 size-6 shrink-0 text-energia" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10" cy="10" r="8.2" /><path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" /></svg><span class="text-base leading-[1.5] tracking-[-0.16px] text-carbono">Procedimentos de isolamento elétrico</span></div>'
                            .'<div class="flex items-start gap-4"><svg class="mt-0.5 size-6 shrink-0 text-energia" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10" cy="10" r="8.2" /><path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" /></svg><span class="text-base leading-[1.5] tracking-[-0.16px] text-carbono">Verificação de ausência de tensão (VAT)</span></div>'
                            .'<div class="flex items-start gap-4"><svg class="mt-0.5 size-6 shrink-0 text-energia" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10" cy="10" r="8.2" /><path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" /></svg><span class="text-base leading-[1.5] tracking-[-0.16px] text-carbono">Imobilização segura para transporte ou intervenção</span></div>'
                            .'</div></div>'
                            .'<div><p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-energia">Aplicação</p>'
                            .'<div class="mt-4 space-y-4">'
                            .'<div class="flex items-start gap-4"><svg class="mt-0.5 size-6 shrink-0 text-energia" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10" cy="10" r="8.2" /><path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" /></svg><span class="text-base leading-[1.5] tracking-[-0.16px] text-carbono">Assistência em estrada</span></div>'
                            .'<div class="flex items-start gap-4"><svg class="mt-0.5 size-6 shrink-0 text-energia" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10" cy="10" r="8.2" /><path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" /></svg><span class="text-base leading-[1.5] tracking-[-0.16px] text-carbono">Operações de recolha de veículos</span></div>'
                            .'<div class="flex items-start gap-4"><svg class="mt-0.5 size-6 shrink-0 text-energia" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10" cy="10" r="8.2" /><path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" /></svg><span class="text-base leading-[1.5] tracking-[-0.16px] text-carbono">Acondicionamento seguro</span></div>'
                            .'<div class="flex items-start gap-4"><svg class="mt-0.5 size-6 shrink-0 text-energia" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10" cy="10" r="8.2" /><path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" /></svg><span class="text-base leading-[1.5] tracking-[-0.16px] text-carbono">Área segregada para veículos elétricos</span></div>'
                            .'</div></div>'
                            .'</div>',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'compacto' => true,
                        'colunas' => 4,
                        'titulo' => 'Para seguradoras, peritos e ',
                        'titulo_destaque' => 'gestores de frota',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Menos perdas totais', 'texto' => 'Muitos abates de veículos elétricos são decisões de precaução por falta de avaliação técnica da bateria. Com avaliação, a conta muda.'],
                            ['numero' => '02', 'titulo' => 'Menos tempo de imobilização', 'texto' => 'E, com ele, menos custo de viatura de substituição.'],
                            ['numero' => '03', 'titulo' => 'Conformidade OEM', 'texto' => 'Conformidade técnica OEM após reparação, documentada.'],
                            ['numero' => '04', 'titulo' => 'Evidências rastreáveis', 'texto' => 'Desde a receção, em sistema antifraude.'],
                        ],
                    ]],
                    ['type' => 'porque_faq', 'data' => [
                        'titulo' => 'Porquê o EVA Collision?',
                        'texto' => 'Um elétrico sinistrado exige avaliação técnica da bateria antes de qualquer decisão — reparar às cegas ou abater por precaução são as duas piores opções.',
                        'imagem' => 'images/eva-car.png',
                        'faq_titulo' => 'Perguntas frequentes',
                        'faqs' => [
                            ['pergunta' => 'Avaliam a bateria antes de decidir por perda total?', 'resposta' => 'Sim. Avaliamos o estado real da bateria e dos sistemas de alta tensão antes de qualquer decisão de reparação ou abate.'],
                            ['pergunta' => 'O que verificam na receção de um veículo sinistrado?', 'resposta' => 'Integridade estrutural da bateria, picos de temperatura por infravermelhos, deteção de fugas de gases e registo das evidências em sistema informático antifraude.'],
                        ],
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Tem um veículo eletrificado sinistrado à espera de decisão?',
                        'texto' => 'Fazemos a avaliação da bateria antes de a perda total ser inevitável.',
                        'icone' => 'car-burst',
                        'cor_icone' => 'lima',
                        'botao_texto' => 'Encaminhar Sinistro',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                    ]],
                ],
            ],

            'eva-powerlab/battery-warranty' => [
                'title' => 'EVA Battery Warranty',
                'meta_title' => 'EVA Battery Warranty — Extensão de Garantia de Bateria | Gocarmat',
                'meta_description' => 'Extensão de garantia até 5 anos para baterias de alta tensão de veículos elétricos e híbridos.',
                'content' => [
                    ['type' => 'servico_hero', 'data' => [
                        'fundo' => 'carbono',
                        'breadcrumb_pai' => 'EVA Powerlab',
                        'breadcrumb_link' => '/eva-powerlab',
                        'breadcrumb_atual' => 'EVA Battery Warranty',
                        'titulo' => 'Cinco anos de tranquilidade sobre a peça mais cara do carro.',
                        'texto' => 'Extensão de garantia até 5 anos para baterias de alta tensão de veículos elétricos e híbridos.',
                        'imagem' => 'images/servico-oleo.jpg',
                        'botoes' => [
                            ['texto' => 'Verificar Elegibilidade', 'link' => '/marcacoes'],
                        ],
                    ]],
                    ['type' => 'texto', 'data' => [
                        'corpo' => '<p class="text-2xl font-bold leading-[1.35] tracking-[-0.16px] text-carbono sm:text-[28px]">A bateria de alta tensão representa uma fatia substancial do valor de um veículo eletrificado.</p>'
                            .'<p>Quando a garantia do fabricante termina, esse risco passa integralmente para o proprietário — e é essa transição que a <span class="font-bold">EVA Battery Warranty</span> cobre.</p>',
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Quer saber se o seu veículo é elegível?',
                        'texto' => 'Fale connosco e verificamos as condições para o seu caso.',
                        'icone' => 'shield',
                        'cor_icone' => 'lima',
                        'botao_texto' => 'Verificar Elegibilidade',
                        'botao_link' => '/marcacoes',
                        'fundo' => 'carbono',
                        'colar_ao_rodape' => true,
                    ]],
                ],
            ],

            'eva-powerlab/certificacao-mv-ber' => [
                'title' => 'Certificação MV-BER',
                'meta_title' => 'Certificação MV-BER EBI/461 — Qualidade OEM | Gocarmat',
                'meta_description' => 'Certificado de Qualidade, Livro de Manutenção Digital e selo MV-BER. Manutenção em oficina independente, documentada segundo os procedimentos do fabricante.',
                'content' => [
                    ['type' => 'servico_hero', 'data' => [
                        'fundo' => 'carbono',
                        'breadcrumb_pai' => 'EVA Powerlab',
                        'breadcrumb_link' => '/eva-powerlab',
                        'breadcrumb_atual' => 'Certificação MV-BER',
                        'titulo' => 'Independente. E documentado como tal.',
                        'texto' => 'Certificação EBI/461 MV-BER: processos de manutenção e reparação com qualidade equivalente à exigida pelos fabricantes, validados por entidade externa independente.',
                        'imagem' => 'images/servico-climatizacao.jpg',
                        'botoes' => [
                            ['texto' => 'Marcar Intervenção Certificada', 'link' => '/marcacoes'],
                            ['texto' => 'O Que Recebe no Fim', 'link' => '/marcacoes'],
                        ],
                    ]],
                    ['type' => 'texto', 'data' => [
                        'corpo' => '<p class="text-2xl font-bold leading-[1.35] tracking-[-0.16px] text-carbono sm:text-[28px]">"Oficina independente" ainda soa, para muita gente, a menos garantias e a um livro de manutenção com um espaço em branco. A certificação MV-BER existe para <span class="text-energia">desfazer essa ideia</span> — e para o provar por escrito.</p>'
                            .'<p>A Gocarmat assegura processos de manutenção e reparação com um nível de qualidade equivalente ao exigido pelos fabricantes automóveis (OEM), suportados por metodologias rigorosas, validação externa e controlo contínuo. A intervenção técnica assenta em procedimentos normalizados, o que garante consistência, segurança e conformidade com os requisitos técnicos dos veículos elétricos e híbridos.</p>',
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'compacto' => true,
                        'colunas' => 3,
                        'titulo' => 'Os três ',
                        'titulo_destaque' => 'pilares',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Formação profissional certificada', 'texto' => 'Qualificação especializada e continuamente avaliada da equipa técnica. Em alta tensão, não é uma formalidade: é a condição de segurança da intervenção.'],
                            ['numero' => '02', 'titulo' => 'Auditoria da dotação técnica', 'texto' => 'Verificação externa de que a oficina dispõe do equipamento exigido para as intervenções que realiza.'],
                            ['numero' => '03', 'titulo' => 'SiGMA — gestão da qualidade', 'texto' => 'Digitalização integral dos processos, com rastreabilidade de cada intervenção e acesso a portais dos fabricantes.'],
                        ],
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'compacto' => true,
                        'colunas' => 4,
                        'titulo' => 'Como isto se traduz na ',
                        'titulo_destaque' => 'prática',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Avaliação contínua', 'texto' => 'De recursos técnicos e humanos.'],
                            ['numero' => '02', 'titulo' => 'Formação certificada', 'texto' => 'Profissional e especializada.'],
                            ['numero' => '03', 'titulo' => 'Software dedicado', 'texto' => 'Controlo de qualidade e acesso a portais OEM.'],
                            ['numero' => '04', 'titulo' => 'Digitalização total', 'texto' => 'Processos digitalizados, com rastreabilidade das intervenções.'],
                            ['numero' => '05', 'titulo' => 'Melhoria contínua', 'texto' => 'Metodologias Kaizen, JIT e EFQM.'],
                        ],
                    ]],
                    ['type' => 'servico_vantagens', 'data' => [
                        'compacto' => true,
                        'colunas' => 3,
                        'titulo' => 'O que recebe no fim da ',
                        'titulo_destaque' => 'intervenção',
                        'itens' => [
                            ['numero' => '01', 'titulo' => 'Certificado de Qualidade', 'texto' => 'Documento do Sistema Europeu de Assistência Especializada e Viaturas Elétricas, que atesta a intervenção realizada segundo procedimentos certificados.'],
                            ['numero' => '02', 'titulo' => 'Livro de Manutenção Digital', 'texto' => 'Registo europeu de manutenção associado à matrícula do veículo — documentado, consultável e verificável por terceiros.'],
                            ['numero' => '03', 'titulo' => 'Selo de Qualidade MV-BER', 'texto' => 'Com código QR, integrado no sistema europeu antifraude SAFE, que permite validar a autenticidade do registo.'],
                        ],
                        'nota' => 'Opcional: lançamento do registo da intervenção no portal do fabricante; averbamento retroativo de intervenções anteriores no Livro de Manutenção Digital.',
                    ]],
                    ['type' => 'texto', 'data' => [
                        'titulo' => 'Porque é que isto interessa a quem vende o carro daqui a três anos',
                        'corpo' => '<p>Um veículo com historial de manutenção completo, digital e verificável defende o seu valor na revenda melhor do que um dossiê de faturas soltas. O LMD e o selo MV-BER existem exatamente para isso: transformar manutenção feita em manutenção provada.</p>',
                    ]],
                    ['type' => 'porque_faq', 'data' => [
                        'titulo' => 'Porquê a Certificação MV-BER?',
                        'texto' => '"Oficina independente" não deveria significar menos garantias. A certificação MV-BER prova por escrito que seguimos procedimentos com qualidade equivalente à dos fabricantes.',
                        'imagem' => 'images/eva-car.png',
                        'faq_titulo' => 'Perguntas frequentes',
                        'faqs' => [
                            ['pergunta' => 'O que recebo no final de uma intervenção certificada?', 'resposta' => 'Certificado de Qualidade, Livro de Manutenção Digital (LMD) e Selo de Qualidade MV-BER.'],
                            ['pergunta' => 'Posso registar intervenções antigas?', 'resposta' => 'Sim, através do averbamento retroativo no Livro de Manutenção Digital.'],
                        ],
                    ]],
                    ['type' => 'cta_icone', 'data' => [
                        'titulo' => 'Manutenção independente, com o mesmo peso documental da rede oficial.',
                        'texto' => '',
                        'icone' => 'certificate',
                        'cor_icone' => 'lima',
                        'botao_texto' => 'Marcar Intervenção',
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
