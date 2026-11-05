<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Passa as imagens dos blocos de public/images para o disco "public".
 *
 * O FileUpload do composer só reconhece ficheiros do disco "public" (storage/).
 * Um bloco que aponte para "images/x.jpg" mostra a imagem no site mas, ao
 * gravar a página no backoffice, o Filament não a encontra e apaga-a. Copiar o
 * ficheiro para storage/app/public/pages e apontar para lá evita isso; o
 * original em public/images fica onde está.
 */
class ImagensDePaginas
{
    /** Chaves de bloco que guardam uma imagem (ou, em "imagens", uma lista). */
    private const CHAVES = ['imagem', 'icone_imagem'];

    /** @param  array<int|string, mixed>  $dados */
    public static function normalizar(array $dados): array
    {
        foreach ($dados as $chave => $valor) {
            if (is_array($valor)) {
                $dados[$chave] = $chave === 'imagens'
                    ? array_map(fn ($v) => is_string($v) ? self::paraDisco($v) : $v, $valor)
                    : self::normalizar($valor);
            } elseif (is_string($valor) && in_array($chave, self::CHAVES, true)) {
                $dados[$chave] = self::paraDisco($valor);
            }
        }

        return $dados;
    }

    /** "images/a/b.jpg" → "pages/a-b.jpg" (copiando o ficheiro); outros caminhos ficam como estão. */
    public static function paraDisco(string $caminho): string
    {
        $origem = public_path($caminho);

        if (! str_starts_with($caminho, 'images/') || ! is_file($origem)) {
            return $caminho;
        }

        $destino = 'pages/'.str_replace('/', '-', substr($caminho, strlen('images/')));
        $disco = Storage::disk('public');

        if (! $disco->exists($destino)) {
            $disco->put($destino, file_get_contents($origem));
        }

        return $destino;
    }
}
