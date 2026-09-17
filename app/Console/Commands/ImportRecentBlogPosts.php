<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\Redirect;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Importa manualmente os artigos do blog WordPress do cliente publicados
 * depois do ultimo export XML disponivel (3 de junho de 2026), recolhidos
 * por scraping das paginas publicas em https://www.gocarmat.pt/blog/ o
 * cliente ainda nao forneceu um novo export XML para estes artigos.
 * Idempotente (chave: wp_id), tal como o gocarmat:import-wordpress.
 */
class ImportRecentBlogPosts extends Command
{
    protected $signature = 'gocarmat:import-recent-blog-posts';

    protected $description = 'Importa os artigos do blog do cliente publicados depois do ultimo export XML (recolhidos manualmente, sem export disponivel)';

    private const CATEGORIAS = [
        'revisao' => 'Revisoes',
    ];

    private const ARTIGOS = array (
  0 => 
  array (
    'wp_id' => 27401,
    'slug' => 'viagens-longas-com-carga-a-importancia-de-ajustar-a-pressao-dos-pneus',
    'title' => 'Viagens longas com carga: a importância de ajustar a pressão dos pneus',
    'body' => '<p>
As férias e as viagens em família implicam, frequentemente, um veículo com a lotação esgotada e a bagageira cheia. No entanto, muitos condutores esquecem-se de que o peso adicional altera drasticamente o comportamento dinâmico do automóvel. Na <a href="https://www.gocarmat.pt/">Gocarmat</a>, reforçamos que ajustar a pressão dos pneus antes de uma viagem longa com carga é um passo vital para garantir a estabilidade e evitar o desgaste excessivo.</p>
<p>Um pneu com a pressão incorreta, quando sujeito a peso elevado e temperaturas de asfalto altas, torna-se um risco direto para a segurança rodoviária.</p>
<h2>Porquê ajustar a pressão em função da carga?</h2>
<p>Quando adicionamos peso ao veículo, a deformação natural do pneu aumenta. Se a pressão não for corrigida, as consequências podem ser graves.</p>
<h3>Estabilidade e precisão na trajetória</h3>
<p>Um carro carregado com pneus sub-pressurizados tende a “oscilar” mais em curvas e em manobras de desvio.</p>
<p><img src="https://www.gocarmat.pt/wp-content/uploads/viagens-longas-2-300x200.jpg"></p>
<p><strong>O ajuste correto da pressão garante que a área de contacto do pneu com o solo permaneça ideal, mantendo a estabilidade do veículo e o conforto de condução, mesmo em situações de emergência.</strong></p>
<h3>Prevenção do sobreaquecimento e rebentamento</h3>
<p>O peso extra faz com que as paredes laterais do pneu flexionem mais, gerando calor excessivo. Em viagens longas de autoestrada, este sobreaquecimento pode levar à degradação da estrutura interna e, no limite, ao rebentamento do pneu. Na <a href="https://www.gocarmat.pt/">Gocarmat</a>, verificamos não só a pressão, mas também o estado geral da borracha para prevenir estes incidentes.</p>
<h2>O impacto direto no consumo e na carteira</h2>
<p>A manutenção preventiva da pressão dos pneus não protege apenas a sua segurança, mas também o seu orçamento de viagem.</p>
<h3>Redução do consumo de combustível</h3>
<p>Pneus com pressão inadequada aumentam a resistência ao rolamento. Com o veículo pesado, o motor tem de realizar um esforço muito superior para manter a velocidade, o que se traduz num aumento significativo do consumo de combustível.</p>
<p><img src="https://www.gocarmat.pt/wp-content/uploads/viagens-longas3-300x224.jpg"></p>
<p>O ajuste correto permite uma condução mais eficiente e económica.</p>
<h3>Longevidade dos pneus e componentes</h3>
<p>Circular com carga e pressão baixa provoca um desgaste irregular e acelerado dos pneus, reduzindo drasticamente a sua vida útil. Além disso, sobrecarrega componentes da suspensão, como os amortecedores e braços de suspensão. Identificar estas necessidades atempadamente evita a substituição prematura de peças complexas.</p>
<h2>Como saber a pressão correta para o seu veículo?</h2>
<p>Cada fabricante define valores específicos para o veículo “vazio” e “carregado”.</p>
<h3>Onde consultar os valores oficiais</h3>
<p>Pode encontrar a tabela de pressões recomendada no manual do proprietário, na face interna da porta do condutor ou na tampa do bocal de combustível. Certifique-se de que faz a medição com os pneus “frios” (circularam menos de 3 km) para obter uma leitura rigorosa.</p>
<h3>Verificação especializada na Gocarmat</h3>
<p>Se tem dúvidas sobre o estado dos seus pneus ou se nota vibrações no volante durante a condução, visite a <a href="https://www.gocarmat.pt/">Gocarmat</a>. Como rede de oficinas multimarca, realizamos um diagnóstico técnico que inclui a avaliação do estado dos pneus, o alinhamento da direção e a suspensão.</p>
<h2>Segurança que começa no solo</h2>
<p>Ajustar a pressão dos pneus leva apenas alguns minutos, mas o seu impacto na segurança de uma viagem longa é incalculável. Não facilite: antes de carregar as malas, garanta que o seu carro está preparado para o esforço adicional.</p>
<p>Visite a <a href="https://www.gocarmat.pt/">Gocarmat</a> para uma verificação técnica completa e assegure uma viagem mais confortável, estável e segura para toda a família.</p>',
    'excerpt' => 'As férias e as viagens em família implicam, frequentemente, um veículo com a lotação esgotada e a bagageira cheia.',
    'meta_description' => 'As férias e as viagens em família implicam, frequentemente, um veículo com a lotação esgotada e a bagageira cheia.',
    'featured_image_url' => 'http://www.gocarmat.pt/wp-content/uploads/viagens-longas.jpg',
    'featured_image_name' => 'viagens-longas',
    'published_at' => '2026-08-24 12:17:54',
    'category' => 'revisao',
    'tags' => 
    array (
      0 => 'consumo-de-combustivel',
      1 => 'gocarmat',
      2 => 'manutencao-preventiva',
      3 => 'oficina-multimarca',
      4 => 'pressao-dos-pneus',
      5 => 'seguranca-rodoviaria',
      6 => 'viagens-com-carga',
    ),
  ),
  1 => 
  array (
    'wp_id' => 27393,
    'slug' => 'como-o-calor-extremo-afeta-a-bateria-e-os-componentes-do-seu-carro',
    'title' => 'Como o calor extremo afeta a bateria e os componentes do seu carro',
    'body' => '<p>
Muitos condutores associam as avarias de bateria apenas ao inverno, mas a verdade é que o calor extremo é um dos maiores inimigos da mecânica automóvel. As temperaturas elevadas aceleram reações químicas e provocam o desgaste prematuro de diversos materiais. Na <a href="https://www.gocarmat.pt/">Gocarmat</a>, acreditamos que compreender como o verão afeta o seu veículo é o primeiro passo para uma manutenção preventiva eficaz.<br>
Identificar estes riscos atempadamente permite evitar imobilizações forçadas e garante que o seu carro mantém a fiabilidade necessária durante a época estival.</p>
<h2>O impacto crítico do calor na bateria</h2>
<p>A bateria é um dos componentes que mais sofre com a subida do mercúrio, sendo a <strong>principal causa de assistência em viagem durante o verão</strong>.</p>
<h3>Evaporação de fluidos e corrosão interna</h3>
<p>O <strong>calor extremo pode provocar a evaporação dos fluidos internos da bateria</strong>, danificando a sua estrutura e reduzindo a capacidade de retenção de carga. Além disso, <strong>as temperaturas altas aceleram o processo de corrosão interna dos terminais</strong>, o que impede a passagem correta da corrente elétrica. Na <a href="https://www.gocarmat.pt/">Gocarmat</a>, verificamos o estado de saúde da sua bateria para garantir que não será surpreendido por uma falha no arranque.</p>
<h3>Esforço acrescido no sistema elétrico</h3>
<p>Com as temperaturas elevadas, o <strong>sistema elétrico é sujeito a uma carga superior devido à utilização intensiva do ar condicionado e dos ventiladores do motor</strong>. Se a bateria já apresentar sinais de desgaste, este esforço adicional pode ser o fator determinante para a sua falha total.</p>
<h2>Outros componentes vulneráveis às altas temperaturas</h2>
<p>Para além da bateria, outros elementos mecânicos e estruturais do veículo sofrem com a exposição prolongada ao sol e ao calor.</p>
<h3>O sistema de refrigeração e o óleo do motor</h3>
<p>O motor depende criticamente do sistema de refrigeração para não atingir temperaturas críticas. <strong>O líquido de refrigeração deve estar no nível correto e com as propriedades químicas intactas para evitar o sobreaquecimento.</strong> Da mesma forma, o óleo do motor torna-se mais fino com o calor, o que pode reduzir a eficácia da lubrificação se o lubrificante estiver degradado.</p>
<p><img src="https://www.gocarmat.pt/wp-content/uploads/calor_extremo-300x200.jpg"></p>
<h3>Desgaste de plásticos, borrachas e pneus</h3>
<p>O <strong>calor extremo resseca as borrachas das escovas limpa-vidros e as tubagens do motor, tornando-as quebradiças.</strong> Os pneus também sofrem uma pressão interna superior, o que, aliado ao asfalto a temperaturas muito elevadas, acelera o desgaste do rasto e aumenta o risco de rebentamento.</p>
<h2>A importância do diagnóstico técnico na Gocarmat</h2>
<p>Como <strong>rede de oficinas multimarca</strong>, a <a href="https://www.gocarmat.pt/">Gocarmat</a> possui o rigor técnico necessário para avaliar todos os pontos sensíveis ao calor.</p>
<h3>Prevenção e segurança em todos os serviços</h3>
<p>A nossa revisão oficial ajuda a detetar precocemente sinais de fadiga em componentes como correias, tubagens e sistemas de carga. Ao confiar na <a href="https://www.gocarmat.pt/">Gocarmat</a>, beneficia de um serviço que coloca a sua <strong>garantia e segurança</strong> em primeiro lugar, assegurando que o seu automóvel está preparado para enfrentar as condições mais adversas.</p>
<h3>Todos os serviços numa única oficina</h3>
<p>Desde o teste de carga da bateria até à verificação do sistema de refrigeração, na <a href="https://www.gocarmat.pt/">Gocarmat</a> encontra todas as soluções necessárias num só local. Oferecemos um acompanhamento especializado para garantir que o calor não compromete a estabilidade e o conforto da sua condução.</p>
<h2>Proteja o seu investimento do sol</h2>
<p>O calor é um desafio silencioso para qualquer automóvel. Agir rapidamente ao primeiro sinal de fadiga elétrica ou mecânica é essencial para evitar reparações complexas e custos elevados.<br>
Visite a <a href="https://www.gocarmat.pt/">Gocarmat</a> e garanta que o seu carro está devidamente protegido e preparado para circular com segurança, independentemente da temperatura exterior.</p>',
    'excerpt' => 'Muitos condutores associam as avarias de bateria apenas ao inverno, mas a verdade é que o calor extremo é um dos maiores inimigos das baterias',
    'meta_description' => 'Muitos condutores associam as avarias de bateria apenas ao inverno, mas a verdade é que o calor extremo é um dos maiores inimigos das baterias',
    'featured_image_url' => 'http://www.gocarmat.pt/wp-content/uploads/calor-extremo-2.jpg',
    'featured_image_name' => 'calor-extremo-bateria',
    'published_at' => '2026-08-24 11:43:17',
    'category' => 'revisao',
    'tags' => 
    array (
      0 => 'ar-condicionado',
      1 => 'climatizacao-automovel',
      2 => 'filtro-de-habitaculo',
      3 => 'gocarmat',
      4 => 'manutencao-preventiva',
      5 => 'saude-e-bem-estar',
      6 => 'seguranca-rodoviaria',
    ),
  ),
  2 => 
  array (
    'wp_id' => 27376,
    'slug' => 'ar-condicionado-carro-seguranca-verao',
    'title' => 'Ar condicionado: muito mais do que apenas conforto no Verão',
    'body' => '<p>
Com a chegada das temperaturas elevadas, <strong>o ar condicionado torna-se o melhor aliado de qualquer condutor</strong>. No entanto, a sua função vai muito além de simplesmente manter o habitáculo fresco. Na <a href="https://www.gocarmat.pt/"><strong>Gocarmat</strong></a>, reforçamos que um sistema de climatização em perfeitas condições é um elemento de segurança ativa e de saúde pública para todos os ocupantes do veículo.</p>
<p>Manter o ar condicionado operacional requer uma atenção técnica que evita avarias dispendiosas no compressor e garante a qualidade do ar que respira.</p>
<h2><strong>O impacto do ar condicionado na segurança rodoviária</strong></h2>
<p>Conduzir com excesso de calor não é apenas desconfortável, <strong>é perigoso</strong>. O bem-estar térmico tem uma relação direta com a capacidade de resposta do condutor.</p>
<h3><strong>Prevenção da fadiga e aumento da concentração</strong></h3>
<p>Estudos indicam que <strong>conduzir com temperaturas interiores acima dos 30°C pode ter um efeito semelhante a uma taxa de alcoolemia ligeira</strong>. O calor <strong>provoca sonolência, aumenta o tempo de reação e potencia a irritabilidade</strong>. Um sistema de ar condicionado eficiente ajuda a manter o condutor alerta e focado na estrada.</p>
<h3><strong>Visibilidade e desembaciamento rápido</strong></h3>
<p>Embora associemos o sistema ao Verão, ele é vital durante todo o ano. O ar condicionado <strong>retira a humidade do ar</strong>, sendo a ferramenta mais eficaz para desembaciar os vidros de forma instantânea em dias de chuva ou de elevada condensação, garantindo uma visibilidade total.</p>
<p><img src="https://www.gocarmat.pt/wp-content/uploads/iStock-1565381891-1-300x200.jpg"></p>
<h2><strong>Saúde e Higienização: O que respira dentro do carro?</strong></h2>
<p>O sistema de ventilação pode acumular bactérias, fungos e poeiras se não for devidamente acompanhado.</p>
<h3><strong>O papel do filtro de habitáculo</strong></h3>
<p>O filtro de habitáculo (ou filtro de pólen) é responsável por reter impurezas e alergénios. Na <a href="https://www.gocarmat.pt/"><strong>Gocarmat</strong></a>, <strong>recomendamos a substituição regular deste filtro para garantir que o ar expelido está livre de partículas nocivas</strong>, algo fundamental para passageiros com problemas respiratórios ou alergias.</p>
<h3><strong>Evitar maus odores e proliferação de fungos</strong></h3>
<p>A condensação que se forma no evaporador do sistema pode criar um ambiente propício ao desenvolvimento de bolor. <strong>Se sente um odor desagradável ao ligar o ar condicionado, é sinal de que o sistema necessita de uma higienização técnica profunda.</strong></p>
<h2><strong>Manutenção na Gocarmat: Eficiência e Longevidade</strong></h2>
<p>Como <strong>rede de oficinas multimarca</strong>, a <a href="https://www.gocarmat.pt/"><strong>Gocarmat</strong></a> possui o conhecimento necessário para intervir em sistemas de climatização de diferentes tecnologias.</p>
<h3><strong>Carregamento de gás e deteção de fugas</strong></h3>
<p>O <strong>sistema de ar condicionado perde</strong>, naturalmente, <strong>uma pequena percentagem de gás refrigerante todos os anos</strong>. Quando o nível está baixo, o compressor é obrigado a trabalhar com maior esforço, aumentando o consumo de combustível e o risco de quebra. Na <a href="https://www.gocarmat.pt/"><strong>Gocarmat</strong></a>, realizamos o carregamento de gás e a verificação de fugas para garantir o desempenho ideal.</p>
<p><img src="https://www.gocarmat.pt/wp-content/uploads/iStock-1318616314-1-300x169.jpg"></p>
<h3><strong>Garantia e Segurança em todos os serviços</strong></h3>
<p>Ao confiar na <a href="https://www.gocarmat.pt/">Gocarmat</a>, tem a certeza de um serviço realizado por profissionais que colocam a <strong>Garantia e Segurança</strong> em primeiro lugar. Oferecemos todos os serviços numa única oficina, desde a verificação do filtro até ao diagnóstico do compressor, assegurando que o seu Verão se mantém fresco e seguro.</p>
<h2><strong>Não espere pelo calor extremo</strong></h2>
<p>A manutenção preventiva do ar condicionado deve ser feita antes das épocas de maior utilização. Um sistema verificado atempadamente consome menos energia e oferece uma proteção superior aos ocupantes do veículo.</p>
<p>Visite a <a href="https://www.gocarmat.pt/"><strong>Gocarmat</strong></a> e garanta que o ar condicionado do seu carro é um verdadeiro aliado do seu conforto e segurança.</p>',
    'excerpt' => 'Com a chegada das temperaturas elevadas, o ar condicionado torna-se o melhor aliado de qualquer condutor. No entanto, a sua função vai muito além de simplesmente manter o habitáculo fresco. Na Gocarmat, reforçamos que um sistema de climatização em perfeitas condições é um elemento de segurança ativa e de saúde pública para todos os ocupantes do veículo.',
    'meta_description' => 'Com a chegada das temperaturas elevadas, o ar condicionado torna-se o melhor aliado de qualquer condutor. No entanto, a sua função vai muito além de simplesmente manter o habitáculo fresco. Na Gocarmat, reforçamos que um sistema de climatização em perfeitas condições é um elemento de segurança ativa e de saúde pública para todos os ocupantes do veículo.',
    'featured_image_url' => 'https://www.gocarmat.pt/wp-content/uploads/iStock-1288366444-1.jpg',
    'featured_image_name' => 'ar-condicionado-verao',
    'published_at' => '2026-07-31 09:26:34',
    'category' => 'revisao',
    'tags' => 
    array (
      0 => 'ar-condicionado',
      1 => 'climatizacao-automovel',
      2 => 'filtro-de-habitaculo',
      3 => 'gocarmat',
      4 => 'manutencao-preventiva',
      5 => 'saude-e-bem-estar',
      6 => 'seguranca-rodoviaria',
    ),
  ),
  3 => 
  array (
    'wp_id' => 27361,
    'slug' => 'check-up-verao-preparar-carro-viagem',
    'title' => 'Check-up de Verão: o que verificar antes de fazer-se à estrada?',
    'body' => '<p>
As férias de verão são, para muitas famílias, o momento de realizar viagens mais longas e enfrentar temperaturas elevadas. No entanto, para garantir que o descanso não é interrompido por avarias inesperadas, a preparação do veículo é um passo essencial.</p>
<p>Na <a href="https://www.gocarmat.pt/"><strong>Gocarmat</strong></a>, acreditamos que uma revisão atempada é a melhor forma de garantir a segurança de todos os passageiros e a fiabilidade do seu automóvel.</p>
<p>Fazer-se à estrada com a certeza de que o seu carro está em perfeitas condições permite-lhe desfrutar da viagem com total tranquilidade.</p>
<h2><strong>Níveis de fluidos e sistema de refrigeração</strong></h2>
<p><strong>O calor intenso coloca uma pressão adicional no motor</strong>. É fundamental <strong>verificar o nível do líquido de refrigeração</strong> (anticongelante), bem como o <strong>óleo do motor</strong>, <strong>o fluido de travões e o líquido limpa-vidros</strong>. Um motor bem lubrificado e refrigerado evita sobreaquecimentos que podem causar danos graves e dispendiosos.</p>
<h3><strong>O estado e a pressão dos pneus</strong></h3>
<p>Os pneus são o único ponto de contacto entre o veículo e a estrada. Antes de carregar as malas, <strong>verifique a pressão de todos os pneus</strong>, incluindo o de reserva, ajustando-a à carga prevista. Na <a href="https://www.gocarmat.pt/"><strong>Gocarmat</strong></a>, avaliamos também o desgaste e a presença de deformações que possam comprometer a aderência em autoestrada.</p>
<h3><strong>Sistema de iluminação e visibilidade</strong></h3>
<p>Ver e ser visto é crucial, especialmente em viagens noturnas. <strong>Confirme se todas as luzes</strong> — médios, máximos, piscas e luzes de travagem — <strong>funcionam corretamente</strong>. Não se esqueça de verificar o estado das escovas limpa-vidros, que podem estar ressequidas devido à exposição solar prolongada.</p>
<p><img src="https://www.gocarmat.pt/wp-content/uploads/carro-estrada-300x200.jpg"></p>
<h2><strong>Conforto e Segurança: O papel do Ar Condicionado</strong></h2>
<p>No verão, o sistema de ar condicionado deixa de ser um luxo para passar a ser um elemento de segurança, prevenindo a fadiga do condutor causada pelo calor excessivo.</p>
<h3><strong>Higienização e carregamento</strong></h3>
<p>Um sistema de ar condicionado que não arrefece corretamente ou que emite odores desagradáveis pode ter filtros sujos ou falta de gás refrigerante. Na <a href="https://www.gocarmat.pt/">Gocarmat</a>, realizamos a <a href="https://www.gocarmat.pt/servicos/climatizacao/">verificação do sistema</a> para garantir um ambiente fresco e saudável dentro do habitáculo durante toda a viagem.</p>
<p><img src="https://www.gocarmat.pt/wp-content/uploads/radio-carro-300x200.jpg"><br>
</p>
<h2><strong>Porque realizar o seu Check-up na Gocarmat?</strong></h2>
<p>Ao escolher a <a href="https://www.gocarmat.pt/"><strong>Gocarmat</strong></a>, beneficia de uma <strong>rede de oficinas multimarca</strong> preparada para tratar do seu veículo com o máximo rigor técnico.<br>
</p>
<h3><strong> Garantia, Segurança e Conveniência</strong></h3>
<p>Oferecemos todos os serviços numa única oficina, o que lhe permite poupar tempo e garantir que nada é deixado ao acaso. A nossa manutenção preventiva foca-se na identificação precoce de anomalias, assegurando que a sua única preocupação nas férias será o destino.</p>
<h3><strong>Rigor técnico ao melhor preço</strong></h3>
<p>Realizar um check-up profissional antes de viajar evita a necessidade de intervenções de emergência, que costumam ser mais complexas e caras. Na <a href="https://www.gocarmat.pt/">Gocarmat</a>, garantimos um serviço de excelência que protege o seu investimento e a segurança da sua família.<br>
</p>
<h2><strong>Prepare as malas, nós preparamos o carro</strong></h2>
<p><strong>Um check-up de verão não deve ser visto como um custo, mas sim como um investimento na sua tranquilidade.</strong> Identificar potenciais problemas antes de iniciar a marcha é a solução mais inteligente para evitar contratempos na estrada.</p>
<p><img src="https://www.gocarmat.pt/wp-content/uploads/preparar-viagem-300x200.jpg"></p>
<p>Visite a <a href="https://www.gocarmat.pt/"><strong>Gocarmat</strong></a> antes de partir para férias e garanta uma condução confortável, estável e segura.</p>',
    'excerpt' => 'As férias de verão são, para muitas famílias, o momento de realizar viagens mais longas e enfrentar temperaturas elevadas. No entanto, para garantir que o descanso não é interrompido por avarias inesperadas, a preparação do veículo é um passo essencial.',
    'meta_description' => 'As férias de verão são, para muitas famílias, o momento de realizar viagens mais longas e enfrentar temperaturas elevadas. No entanto, para garantir que o descanso não é interrompido por avarias inesperadas, a preparação do veículo é um passo essencial.',
    'featured_image_url' => 'https://www.gocarmat.pt/wp-content/uploads/familia-em-viagem.jpg',
    'featured_image_name' => 'check-up-verao',
    'published_at' => '2026-07-27 09:56:48',
    'category' => 'revisao',
    'tags' => 
    array (
      0 => 'ar-condicionado',
      1 => 'check-up-de-verao',
      2 => 'gocarmat',
      3 => 'manutencao-preventiva',
      4 => 'pneus',
      5 => 'seguranca-rodoviaria',
      6 => 'viagens-de-ferias',
    ),
  ),
);

    public function handle(): int
    {
        foreach (self::ARTIGOS as $a) {
            $featuredImage = $this->descarregarImagem($a['featured_image_url'], $a['featured_image_name']);
            $body = $this->localizarImagensInline($a['body'], $a['slug']);

            $post = Post::updateOrCreate(
                ['wp_id' => $a['wp_id']],
                [
                    'title' => $a['title'],
                    'slug' => $a['slug'],
                    'excerpt' => Str::limit(trim($a['excerpt']), 400, '...'),
                    'body' => $body,
                    'featured_image' => $featuredImage,
                    'status' => 'published',
                    'published_at' => $a['published_at'],
                    'meta_description' => Str::limit(trim($a['meta_description']), 320, ''),
                ],
            );

            $category = Category::firstOrCreate(
                ['slug' => $a['category']],
                ['name' => self::CATEGORIAS[$a['category']] ?? ucfirst($a['category'])],
            );
            $post->categories()->sync([$category->id]);

            $tagIds = [];
            foreach ($a['tags'] as $tagSlug) {
                $tag = Tag::firstOrCreate(['slug' => $tagSlug], ['name' => ucwords(str_replace('-', ' ', $tagSlug))]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);

            Redirect::updateOrCreate(
                ['from_path' => '/'.$a['slug']],
                ['to_path' => '/blog/'.$a['slug']],
            );

            $this->line("  {$post->id}: {$a['title']}");
        }

        $this->info('Importados '.count(self::ARTIGOS).' artigos. Total publicados: '.Post::published()->count());

        return self::SUCCESS;
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
                $this->warn("    imagem falhou ({$resposta->status()}): {$url}");
                \Illuminate\Support\Facades\Log::warning("import-recent-blog-posts: imagem falhou ({$resposta->status()}): {$url}");

                return null;
            }

            $gravado = Storage::disk('public')->put($path, $resposta->body());

            if (! $gravado) {
                $this->warn("    falhou a gravar a imagem em disco: {$path}");
                \Illuminate\Support\Facades\Log::warning("import-recent-blog-posts: falhou a gravar a imagem em disco: {$path}");

                return null;
            }

            return $path;
        } catch (\Throwable $e) {
            $this->warn("    imagem falhou: {$url} - {$e->getMessage()}");
            \Illuminate\Support\Facades\Log::warning("import-recent-blog-posts: imagem falhou: {$url} - {$e->getMessage()}");

            return null;
        }
    }

    private function localizarImagensInline(string $html, string $slug): string
    {
        return preg_replace_callback(
            '/src="(https?:\/\/(?:www\.)?gocarmat\.pt\/wp-content\/uploads\/[^"]+)"/i',
            function (array $m) use ($slug) {
                $url = html_entity_decode($m[1]);
                $nome = pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME);
                $path = $this->descarregarImagem($url, 'inline/'.$slug.'-'.Str::slug($nome));

                return $path ? 'src="'.Storage::disk('public')->url($path).'"' : $m[0];
            },
            $html,
        );
    }
}
