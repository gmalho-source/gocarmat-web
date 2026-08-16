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

/**
 * Ações pontuais, além das tarefas de manutenção. Existem porque este
 * alojamento não tem terminal — sem isto não haveria forma de criar um
 * utilizador do backoffice ou popular as páginas depois de um deploy.
 */
$acao = $_GET['acao'] ?? null;

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

/**
 * Instala a aplicação a partir do repositório clonado pelo cPanel.
 *
 * Substitui o "Deploy HEAD Commit" do painel, que bloqueia sempre que a árvore
 * do repositório fica suja. Copia apenas o código: .env, base de dados e
 * storage (imagens do backoffice) nunca são tocados.
 */
if ($acao === 'instalar') {
    echo "\n-- Instalar ficheiros a partir do repositório\n";

    $origem = $_GET['origem'] ?? '/home/gocarmat/repositories/gocarmat-web';

    if (! is_dir($origem) || ! is_file($origem.'/artisan')) {
        http_response_code(500);
        exit("Repositório não encontrado em {$origem}\n");
    }

    $preservar = ['.env', 'storage', 'public/storage', 'database/database.sqlite', '.git', '.github', '.cpanel', 'node_modules'];
    $copiados = 0;
    $erros = 0;

    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($origem, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($it as $item) {
        $relativo = str_replace('\\', '/', ltrim(str_replace($origem, '', $item->getPathname()), '/\\'));

        foreach ($preservar as $p) {
            if ($relativo === $p || str_starts_with($relativo, $p.'/')) {
                continue 2;
            }
        }

        $destino = $raiz.'/'.$relativo;

        if ($item->isDir()) {
            if (! is_dir($destino)) {
                @mkdir($destino, 0755, true);
            }

            continue;
        }

        if (! is_dir(dirname($destino))) {
            @mkdir(dirname($destino), 0755, true);
        }

        // Só copia o que mudou de facto. A comparação é pelo conteúdo (hash) e
        // não pela data: os deploys anteriores do cPanel deixaram ficheiros com
        // data mais recente que a do repositório, o que levaria a saltar
        // ficheiros desatualizados e a misturar versões da aplicação.
        if (is_file($destino)
            && filesize($destino) === $item->getSize()
            && md5_file($destino) === md5_file($item->getPathname())) {
            continue;
        }

        @copy($item->getPathname(), $destino) ? $copiados++ : $erros++;
    }

    echo "{$copiados} ficheiros atualizados".($erros ? ", {$erros} falharam" : '')."\n";

    // O código mudou: as caches de bootstrap têm de desaparecer antes de arrancar
    foreach (glob($raiz.'/bootstrap/cache/*.php') ?: [] as $ficheiro) {
        @unlink($ficheiro);
    }
}

// Diagnóstico: últimas linhas do log de erros (sem terminal, é a única forma
// de perceber o que se passou depois de um deploy).
if ($acao === 'log') {
    $log = $raiz.'/storage/logs/laravel.log';
    echo "\n-- Últimas linhas de ".basename($log)."\n";

    if (! is_readable($log)) {
        echo "(sem log)\n";
    } else {
        $linhas = @file($log) ?: [];
        echo implode('', array_slice($linhas, -((int) ($_GET['linhas'] ?? 40))));
    }

    exit;
}

// Ações pontuais, pedidas explicitamente por ?acao=
if ($acao === 'criar-admin') {
    echo "\n-- Criar/repor utilizador do backoffice\n";
    try {
        \Illuminate\Support\Facades\Artisan::call('gocarmat:criar-admin', array_filter([
            '--email' => $_GET['email'] ?? null,
            '--nome' => $_GET['nome'] ?? null,
        ]));
        echo trim(\Illuminate\Support\Facades\Artisan::output())."\n";
    } catch (\Throwable $e) {
        $falhou = true;
        echo 'ERRO: '.$e->getMessage()."\n";
    }
}

if ($acao === 'seed-pages') {
    echo "\n-- Criar páginas do site na base de dados\n";
    try {
        \Illuminate\Support\Facades\Artisan::call('gocarmat:seed-pages', array_filter([
            '--force' => isset($_GET['force']) ? true : null,
        ]));
        echo trim(\Illuminate\Support\Facades\Artisan::output())."\n";
    } catch (\Throwable $e) {
        $falhou = true;
        echo 'ERRO: '.$e->getMessage()."\n";
    }
}

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
