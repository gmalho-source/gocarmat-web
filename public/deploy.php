<?php

/**
 * Endpoint de pós-deploy — corre as tarefas de manutenção depois do upload dos
 * ficheiros pelo GitHub Actions (migrações e reconstrução de caches).
 *
 * É deliberadamente independente do framework: se um deploy partir a aplicação,
 * este ficheiro ainda consegue arrancar e reportar o erro.
 *
 * Segurança: exige o segredo definido em DEPLOY_SECRET no .env do servidor
 * (nunca no repositório). Sem segredo definido, recusa sempre.
 *
 * Uso: POST /deploy.php  com o cabeçalho  X-Deploy-Secret: <segredo>
 */

@ini_set('memory_limit', '512M');
@set_time_limit(600);
header('Content-Type: text/plain; charset=utf-8');

$raiz = dirname(__DIR__);

/** Lê uma chave do .env sem depender do Laravel. */
function envDireto(string $chave, string $ficheiro): ?string
{
    if (! is_readable($ficheiro)) {
        return null;
    }
    foreach (file($ficheiro, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
        if (str_starts_with(trim($linha), $chave.'=')) {
            return trim(substr(trim($linha), strlen($chave) + 1), " \t\"'");
        }
    }

    return null;
}

$segredo = envDireto('DEPLOY_SECRET', $raiz.'/.env');
$enviado = $_SERVER['HTTP_X_DEPLOY_SECRET'] ?? '';

if ($segredo === null || $segredo === '') {
    http_response_code(503);
    exit("DEPLOY_SECRET não está definido no .env do servidor.\n");
}

if (! is_string($enviado) || ! hash_equals($segredo, $enviado)) {
    http_response_code(403);
    exit("Não autorizado.\n");
}

echo "== Pós-deploy GOCARMAT ==\n";

try {
    require $raiz.'/vendor/autoload.php';
    $app = require_once $raiz.'/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
} catch (\Throwable $e) {
    http_response_code(500);
    exit("Falha ao arrancar a aplicação: ".$e->getMessage()."\n");
}

$correr = function (string $comando, array $args = []) {
    \Illuminate\Support\Facades\Artisan::call($comando, $args);

    return trim(\Illuminate\Support\Facades\Artisan::output());
};

$falhou = false;

foreach ([
    'Limpar caches' => ['optimize:clear', []],
    'Ligação do storage' => ['storage:link', ['--force' => true]],
    'Migrações' => ['migrate', ['--force' => true]],
    'Cache de configuração' => ['config:cache', []],
    'Cache de rotas' => ['route:cache', []],
    'Cache de vistas' => ['view:cache', []],
] as $titulo => [$comando, $args]) {
    echo "\n-- {$titulo}\n";
    try {
        echo ($correr($comando, $args) ?: 'ok')."\n";
    } catch (\Throwable $e) {
        $falhou = true;
        echo "ERRO: ".$e->getMessage()."\n";
    }
}

if ($falhou) {
    http_response_code(500);
    echo "\n== TERMINOU COM ERROS ==\n";
} else {
    echo "\n== DEPLOY CONCLUÍDO ==\n";
}
