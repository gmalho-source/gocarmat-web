<?php

/**
 * Endpoint de auto-atualização.
 *
 * Este servidor não tem SSH nem terminal, e o FTP corta a ligação ao fim de ~2
 * minutos — não dá para empurrar ficheiros. A abordagem é a inversa: o servidor
 * descarrega do GitHub um único ficheiro comprimido com a aplicação já compilada
 * (branch "deploy"), instala-o e corre as tarefas de manutenção.
 *
 * Chamado pelo GitHub Actions no fim de cada build.
 *
 * Configuração necessária no .env do servidor:
 *   DEPLOY_SECRET=<segredo partilhado com o GitHub Actions>
 *   DEPLOY_GITHUB_TOKEN=<token com permissão de leitura no repositório>
 *   DEPLOY_REPO=gmalho-source/gocarmat-web
 *   DEPLOY_BRANCH=deploy
 */

@ini_set('memory_limit', '512M');
@set_time_limit(900);
@ini_set('zlib.output_compression', '0');
while (ob_get_level() > 0) {
    ob_end_flush();
}
header('Content-Type: text/plain; charset=utf-8');

$raiz = dirname(__DIR__);

function lerEnv(string $chave, string $ficheiro, ?string $omissao = null): ?string
{
    if (! is_readable($ficheiro)) {
        return $omissao;
    }
    foreach (file($ficheiro, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
        $linha = trim($linha);
        if (str_starts_with($linha, $chave.'=')) {
            return trim(substr($linha, strlen($chave) + 1), " \t\"'");
        }
    }

    return $omissao;
}

$env = $raiz.'/.env';
$segredo = lerEnv('DEPLOY_SECRET', $env);
$token = lerEnv('DEPLOY_GITHUB_TOKEN', $env);
$repo = lerEnv('DEPLOY_REPO', $env, 'gmalho-source/gocarmat-web');
$branch = lerEnv('DEPLOY_BRANCH', $env, 'deploy');

if (! $segredo) {
    http_response_code(503);
    exit("DEPLOY_SECRET não definido no .env do servidor.\n");
}
if (! hash_equals($segredo, (string) ($_SERVER['HTTP_X_DEPLOY_SECRET'] ?? ''))) {
    http_response_code(403);
    exit("Não autorizado.\n");
}
if (! $token) {
    http_response_code(503);
    exit("DEPLOY_GITHUB_TOKEN não definido no .env do servidor.\n");
}
if (! class_exists('ZipArchive')) {
    http_response_code(503);
    exit("A extensão PHP 'zip' não está instalada — é necessária para o deploy.\n");
}

function passo(string $t): void
{
    echo "\n-- {$t}\n";
    flush();
}

/** Apaga uma pasta recursivamente. */
function apagarPasta(string $dir): void
{
    if (! is_dir($dir)) {
        return;
    }
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($it as $f) {
        $f->isDir() ? @rmdir($f->getPathname()) : @unlink($f->getPathname());
    }
    @rmdir($dir);
}

echo "== Deploy GOCARMAT ==\nrepo: {$repo}  branch: {$branch}\n";

$tmp = $raiz.'/storage/app/deploy-tmp';
$zip = $raiz.'/storage/app/deploy.zip';
apagarPasta($tmp);
@unlink($zip);

// 1. Descarregar o pacote do GitHub
passo('Descarregar do GitHub');
$fh = fopen($zip, 'wb');
$ch = curl_init("https://api.github.com/repos/{$repo}/zipball/{$branch}");
curl_setopt_array($ch, [
    CURLOPT_FILE => $fh,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT => 300,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer '.$token,
        'User-Agent: gocarmat-deploy',
        'Accept: application/vnd.github+json',
    ],
]);
$ok = curl_exec($ch);
$codigo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$erroCurl = curl_error($ch);
curl_close($ch);
fclose($fh);

if (! $ok || $codigo !== 200) {
    http_response_code(500);
    @unlink($zip);
    exit("Falha ao descarregar (HTTP {$codigo}) {$erroCurl}\nVerifique DEPLOY_GITHUB_TOKEN e se o branch existe.\n");
}
echo 'descarregado: '.number_format(filesize($zip) / 1048576, 1)." MB\n";

// 2. Extrair
passo('Extrair');
$za = new ZipArchive;
if ($za->open($zip) !== true) {
    http_response_code(500);
    exit("Ficheiro comprimido inválido.\n");
}
@mkdir($tmp, 0755, true);
$za->extractTo($tmp);
$total = $za->numFiles;
$za->close();
@unlink($zip);

// O zipball do GitHub embrulha tudo numa pasta com hash
$conteudo = glob($tmp.'/*', GLOB_ONLYDIR);
$origem = $conteudo[0] ?? null;
if (! $origem || ! is_file($origem.'/artisan')) {
    http_response_code(500);
    apagarPasta($tmp);
    exit("O pacote não parece conter a aplicação (artisan não encontrado).\n");
}
echo "{$total} ficheiros extraídos\n";

// 3. Instalar por cima, preservando o que é do servidor
passo('Instalar ficheiros');
$preservar = ['.env', 'storage', 'public/storage', 'database/database.sqlite', '.git'];
$copiados = 0;

$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($origem, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);
foreach ($it as $item) {
    $relativo = ltrim(str_replace($origem, '', $item->getPathname()), '/\\');
    $relativo = str_replace('\\', '/', $relativo);

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
    } else {
        $pasta = dirname($destino);
        if (! is_dir($pasta)) {
            @mkdir($pasta, 0755, true);
        }
        if (@copy($item->getPathname(), $destino)) {
            $copiados++;
        }
    }
}
apagarPasta($tmp);
echo "{$copiados} ficheiros instalados\n";

// 4. Tarefas de manutenção
passo('Tarefas de manutenção');
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
    ['optimize:clear', []],
    ['storage:link', ['--force' => true]],
    ['migrate', ['--force' => true]],
    ['config:cache', []],
    ['route:cache', []],
    ['view:cache', []],
] as [$comando, $args]) {
    try {
        \Illuminate\Support\Facades\Artisan::call($comando, $args);
        echo "  {$comando}: ok\n";
    } catch (\Throwable $e) {
        $falhou = true;
        echo "  {$comando}: ERRO — ".$e->getMessage()."\n";
    }
    flush();
}

if ($falhou) {
    http_response_code(500);
    exit("\n== TERMINOU COM ERROS ==\n");
}

echo "\n== DEPLOY CONCLUÍDO ==\n";
