<?php

/**
 * Tarefas de pós-deploy (migrações e caches).
 *
 * O código chega ao servidor pelo Git Version Control do cPanel ("Update from
 * Remote"); este endpoint faz o resto, que exige linha de comandos — coisa que
 * não temos neste alojamento (sem SSH nem terminal).
 *
 * É deliberadamente independente do framework: se um deploy partir a aplicação,
 * este ficheiro ainda arranca e reporta o erro.
 *
 * Uso: POST /deploy.php  com o cabeçalho  X-Deploy-Secret: <segredo>
 * O segredo vem de DEPLOY_SECRET no .env do servidor (nunca no repositório).
 */

@ini_set('memory_limit', '512M');
@set_time_limit(600);
header('Content-Type: text/plain; charset=utf-8');

$raiz = dirname(__DIR__);

function lerEnv(string $chave, string $ficheiro): ?string
{
    if (! is_readable($ficheiro)) {
        return null;
    }
    foreach (file($ficheiro, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
        $linha = trim($linha);
        if (str_starts_with($linha, $chave.'=')) {
            return trim(substr($linha, strlen($chave) + 1), " \t\"'");
        }
    }

    return null;
}

$segredo = lerEnv('DEPLOY_SECRET', $raiz.'/.env');

if (! $segredo) {
    http_response_code(503);
    exit("DEPLOY_SECRET não está definido no .env do servidor.\n");
}

if (! hash_equals($segredo, (string) ($_SERVER['HTTP_X_DEPLOY_SECRET'] ?? ''))) {
    http_response_code(403);
    exit("Não autorizado.\n");
}

echo "== Pós-deploy GOCARMAT ==\n";

// As caches de bootstrap são geradas a partir do vendor/ anterior. Se o deploy
// trocou as dependências (ex: passou a --no-dev), ficam a apontar para classes
// que já não existem e a aplicação nem arranca. Têm de ser removidas primeiro.
echo "\n-- Limpar caches de bootstrap\n";
foreach (glob($raiz.'/bootstrap/cache/*.php') ?: [] as $ficheiro) {
    echo '  removido: '.basename($ficheiro).(@unlink($ficheiro) ? '' : ' (FALHOU)')."\n";
}

try {
    require $raiz.'/vendor/autoload.php';
    $app = require $raiz.'/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
} catch (\Throwable $e) {
    http_response_code(500);
    exit("Falha ao arrancar a aplicação: ".$e->getMessage()."\n");
}

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
        \Illuminate\Support\Facades\Artisan::call($comando, $args);
        echo (trim(\Illuminate\Support\Facades\Artisan::output()) ?: 'ok')."\n";
    } catch (\Throwable $e) {
        $falhou = true;
        echo 'ERRO: '.$e->getMessage()."\n";
    }
}

if ($falhou) {
    http_response_code(500);
    echo "\n== TERMINOU COM ERROS ==\n";
} else {
    echo "\n== DEPLOY CONCLUÍDO ==\n";
}
