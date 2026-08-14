<?php

namespace App\Support;

/**
 * Apoio aos blocos do composer de páginas.
 *
 * Centraliza a tradução das opções escolhidas no backoffice para as classes do
 * design system, garantindo que qualquer combinação escolhida pelo cliente
 * continua legível e fiel à marca.
 */
class Blocos
{
    /** @return array{sec: string, titulo: string, texto: string} */
    public static function fundo(?string $chave): array
    {
        $mapa = [
            'branco' => ['sec' => 'bg-white', 'titulo' => 'text-carbono', 'texto' => 'text-carbono'],
            'cloud' => ['sec' => 'bg-cloud', 'titulo' => 'text-carbono', 'texto' => 'text-carbono'],
            'gelo' => ['sec' => 'bg-gelo', 'titulo' => 'text-carbono', 'texto' => 'text-carbono'],
            'energia' => ['sec' => 'bg-energia', 'titulo' => 'text-white', 'texto' => 'text-gelo'],
            'carbono' => ['sec' => 'bg-carbono', 'titulo' => 'text-white', 'texto' => 'text-gelo'],
            'lima' => ['sec' => 'bg-lima', 'titulo' => 'text-carbono', 'texto' => 'text-carbono'],
        ];

        return $mapa[$chave ?? 'branco'] ?? $mapa['branco'];
    }

    /** O fundo é escuro? Usado para escolher a variante dos botões e pills. */
    public static function escuro(?string $chave): bool
    {
        return in_array($chave, ['carbono', 'energia'], true);
    }

    /**
     * URL de uma imagem de bloco.
     *
     * As imagens carregadas no backoffice vivem em storage/; as originais do
     * design vivem em public/images. Assim um bloco funciona com ambas, e o
     * cliente pode substituir a imagem original sem partir nada.
     */
    public static function imagem(?string $caminho, ?string $omissao = null): ?string
    {
        $valor = filled($caminho) ? $caminho : $omissao;

        if (blank($valor)) {
            return null;
        }

        return str_starts_with($valor, 'images/')
            ? asset($valor)
            : asset('storage/'.$valor);
    }
}
