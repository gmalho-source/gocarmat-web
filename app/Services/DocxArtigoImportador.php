<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Converte um artigo escrito em Word (.docx) no HTML usado em Post::body,
 * guardando automaticamente as imagens embutidas no documento — ao
 * contrário do Markdown, o .docx já traz as imagens dentro do próprio
 * ficheiro, por isso não é preciso nenhum URL público à parte.
 *
 * Cobre o que os artigos do blog GOCARMAT realmente usam: parágrafos,
 * títulos (Heading 1-3), negrito, itálico, hiperligações, imagens e listas
 * simples — não é um conversor DOCX→HTML genérico (não trata tabelas,
 * notas de rodapé, numeração avançada, etc.).
 */
class DocxArtigoImportador
{
    private const NS_W = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';

    private const NS_R = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

    private \ZipArchive $zip;

    private \DOMXPath $xpath;

    /** @var array<string, array{tipo: string, alvo: string}> */
    private array $relacoes = [];

    /**
     * @return array{
     *     titulo: ?string,
     *     body: string,
     *     imagem_preambulo: ?string,
     *     categoria: ?string,
     *     tags: array<int, string>,
     *     slug_sugerido: ?string,
     * }
     */
    public function processar(string $caminhoDocx, string $slug): array
    {
        $this->zip = new \ZipArchive();

        if ($this->zip->open($caminhoDocx) !== true) {
            throw new \RuntimeException('Não foi possível abrir o ficheiro .docx.');
        }

        try {
            $this->carregarRelacoes();

            $dom = new \DOMDocument();
            $dom->loadXML($this->zip->getFromName('word/document.xml'));

            $xpath = new \DOMXPath($dom);
            $xpath->registerNamespace('w', self::NS_W);
            $this->xpath = $xpath;

            $paragrafos = $xpath->query('//w:body/w:p');

            $titulo = null;
            $imagemPreambulo = null;
            $imagemDestaqueFinal = null;
            $proximaImagemEhDestaque = false;
            $blocosHtml = [];
            $metadados = [];
            $contadorImagens = 0;
            $itemAnterior = false;

            foreach ($paragrafos as $p) {
                $estilo = $this->estiloDoParagrafo($p);
                $ehTitulo = $titulo === null && $this->ehEstiloDeTitulo($estilo);
                $textoSimples = $this->textoSemFormatacao($p);

                // Metadados no fim do documento (Categorias/Tags/Palavras-chave/Slug),
                // no mesmo formato que já usamos nos artigos: "Rótulo: valor".
                if ($chave = $this->chaveDeMetadado($textoSimples)) {
                    if ($chave === 'imagem_destaque_marcador') {
                        $proximaImagemEhDestaque = true;
                    } else {
                        $metadados[$chave] = trim(Str::after($textoSimples, ':'));
                    }

                    continue;
                }

                $imagens = $this->imagensDoParagrafo($p, $slug, $contadorImagens);

                // A imagem logo a seguir a um rótulo "Imagem de topo/destaque:"
                // (normalmente no fim do artigo) é a imagem de destaque, não
                // uma imagem do corpo do artigo.
                if ($imagens && $proximaImagemEhDestaque) {
                    $imagemDestaqueFinal ??= $imagens[0];
                    $proximaImagemEhDestaque = false;

                    continue;
                }

                if ($ehTitulo) {
                    $titulo = $textoSimples ?: null;

                    continue;
                }

                // Imagens antes do título (ex: a "imagem de topo" que serve de
                // destaque) não entram no corpo — ficam só como sugestão.
                if ($titulo === null) {
                    if ($imagens) {
                        $imagemPreambulo ??= $imagens[0];
                    }

                    continue;
                }

                if ($imagens) {
                    foreach ($imagens as $caminhoImagem) {
                        $blocosHtml[] = '<p><img src="'.Storage::disk('public')->url($caminhoImagem).'" alt=""></p>';
                    }

                    continue;
                }

                if ($textoSimples === '') {
                    continue;
                }

                $htmlParagrafo = $this->paragrafoParaHtml($p);
                $ehItemLista = $this->paragrafoEhItemDeLista($p);

                if ($ehItemLista) {
                    $blocosHtml[] = ($itemAnterior ? '' : '<ul>')."<li>{$htmlParagrafo}</li>";
                    $itemAnterior = true;

                    continue;
                }

                if ($itemAnterior) {
                    $blocosHtml[count($blocosHtml) - 1] .= '</ul>';
                    $itemAnterior = false;
                }

                $tag = match ($estilo) {
                    'Heading1' => 'h2', // h1 fica reservado ao título da página
                    'Heading2' => 'h3',
                    'Heading3' => 'h4',
                    default => 'p',
                };

                $blocosHtml[] = "<{$tag}>{$htmlParagrafo}</{$tag}>";
            }

            if ($itemAnterior) {
                $blocosHtml[count($blocosHtml) - 1] .= '</ul>';
            }

            return [
                'titulo' => $titulo,
                'body' => implode("\n", $blocosHtml),
                'imagem_preambulo' => $imagemDestaqueFinal ?? $imagemPreambulo,
                'categoria' => $metadados['categoria'] ?? null,
                'tags' => filled($metadados['tags'] ?? null)
                    ? array_values(array_filter(array_map(fn ($t) => trim($t, " \t\n\r\0\x0B."), explode(',', $metadados['tags']))))
                    : [],
                'slug_sugerido' => filled($metadados['slug'] ?? null) ? Str::slug($metadados['slug']) : null,
            ];
        } finally {
            $this->zip->close();
        }
    }

    private function carregarRelacoes(): void
    {
        $xml = $this->zip->getFromName('word/_rels/document.xml.rels');

        if (! $xml) {
            return;
        }

        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        foreach ($dom->getElementsByTagName('Relationship') as $rel) {
            $tipo = str_contains((string) $rel->getAttribute('Type'), '/image') ? 'imagem'
                : (str_contains((string) $rel->getAttribute('Type'), '/hyperlink') ? 'hiperligacao' : 'outro');

            $this->relacoes[$rel->getAttribute('Id')] = [
                'tipo' => $tipo,
                'alvo' => $rel->getAttribute('Target'),
            ];
        }
    }

    private function estiloDoParagrafo(\DOMElement $p): ?string
    {
        $node = $this->xpath->query('.//w:pPr/w:pStyle/@w:val', $p)->item(0);

        return $node?->nodeValue;
    }

    private function ehEstiloDeTitulo(?string $estilo): bool
    {
        return in_array($estilo, ['Title', 'Heading1'], true);
    }

    private function paragrafoEhItemDeLista(\DOMElement $p): bool
    {
        return $this->xpath->query('.//w:pPr/w:numPr', $p)->length > 0;
    }

    private function textoSemFormatacao(\DOMElement $p): string
    {
        $texto = '';
        foreach ($this->xpath->query('.//w:t', $p) as $t) {
            $texto .= $t->nodeValue;
        }

        return trim($texto);
    }

    /** @return array<int, string> caminhos no disco 'public' das imagens guardadas */
    private function imagensDoParagrafo(\DOMElement $p, string $slug, int &$contador): array
    {
        $caminhos = [];

        foreach ($this->xpath->query('.//w:drawing//*[local-name()="blip"]', $p) as $blip) {
            $rId = $blip->getAttributeNS(self::NS_R, 'embed');
            $relacao = $this->relacoes[$rId] ?? null;

            if (! $relacao || $relacao['tipo'] !== 'imagem') {
                continue;
            }

            $dadosImagem = $this->zip->getFromName('word/'.$relacao['alvo']);

            if ($dadosImagem === false) {
                continue;
            }

            $contador++;
            $ext = strtolower(pathinfo($relacao['alvo'], PATHINFO_EXTENSION)) ?: 'jpg';
            $caminho = "blog/inline/{$slug}-{$contador}.{$ext}";

            try {
                if (Storage::disk('public')->put($caminho, $dadosImagem)) {
                    $caminhos[] = $caminho;
                }
            } catch (\Throwable $e) {
                Log::warning("importar-docx: falhou a gravar imagem {$caminho} - {$e->getMessage()}");
            }
        }

        return $caminhos;
    }

    /** Converte o texto de um parágrafo (runs + negrito/itálico/links) em HTML inline. */
    private function paragrafoParaHtml(\DOMElement $p): string
    {
        $html = '';

        foreach ($p->childNodes as $filho) {
            if (! $filho instanceof \DOMElement) {
                continue;
            }

            if ($filho->localName === 'hyperlink') {
                $rId = $filho->getAttributeNS(self::NS_R, 'id');
                $href = $this->relacoes[$rId]['alvo'] ?? null;
                $textoLink = $this->textoDoRunOuGrupo($filho);

                $html .= $href
                    ? '<a href="'.e($href).'">'.e($textoLink).'</a>'
                    : e($textoLink);

                continue;
            }

            if ($filho->localName === 'r') {
                $html .= $this->runParaHtml($filho);
            }
        }

        return $html;
    }

    private function textoDoRunOuGrupo(\DOMElement $elemento): string
    {
        $texto = '';
        foreach ($this->xpath->query('.//w:t', $elemento) as $t) {
            $texto .= $t->nodeValue;
        }

        return $texto;
    }

    private function runParaHtml(\DOMElement $run): string
    {
        $texto = '';
        foreach ($run->childNodes as $no) {
            if (! $no instanceof \DOMElement) {
                continue;
            }

            if ($no->localName === 't') {
                $texto .= $no->nodeValue;
            } elseif ($no->localName === 'br') {
                $texto .= "\n";
            } elseif ($no->localName === 'tab') {
                $texto .= "\t";
            }
        }

        if ($texto === '') {
            return '';
        }

        $texto = nl2br(e($texto));

        $negrito = $this->xpath->query('.//w:rPr/w:b[not(@w:val="0") and not(@w:val="false")]', $run)->length > 0;
        $italico = $this->xpath->query('.//w:rPr/w:i[not(@w:val="0") and not(@w:val="false")]', $run)->length > 0;

        if ($negrito) {
            $texto = "<strong>{$texto}</strong>";
        }

        if ($italico) {
            $texto = "<em>{$texto}</em>";
        }

        return $texto;
    }

    /** Identifica linhas "Categorias do artigo: ...", "Tags: ...", etc. */
    private function chaveDeMetadado(string $texto): ?string
    {
        return match (true) {
            (bool) preg_match('/^categorias?\s+do\s+artigo\s*:/i', $texto) => 'categoria',
            (bool) preg_match('/^tags?\s*:/i', $texto) => 'tags',
            (bool) preg_match('/^slug\s*:/i', $texto) => 'slug',
            // Rótulo (sem valor a seguir) que marca a imagem seguinte como a
            // imagem de destaque do artigo, não uma imagem do corpo.
            (bool) preg_match('/^imagem\s+de\s+(topo|destaque)\b\s*:?\s*$/i', $texto) => 'imagem_destaque_marcador',
            // Reconhecida para não ficar publicada no corpo, mas sem campo
            // próprio no formulário — é só uma lista de palavras-chave SEO.
            (bool) preg_match('/^palavras[\s-]?chave\b.*:/i', $texto) => 'palavras_chave',
            default => null,
        };
    }
}
