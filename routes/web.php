<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GasOrderController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Models\Page;
use App\Models\Redirect;
use Illuminate\Support\Facades\Route;

// Landing Repsol Gás no subdomínio próprio (repsol-gas.gocarmat.pt): a raiz é a
// página e o formulário envia para a própria raiz. Tudo o resto volta ao site
// principal, para o subdomínio não duplicar o site inteiro. Tem de vir primeiro
// para o "{caminho}" não ser apanhado pelas rotas do domínio principal.
Route::domain(config('app.repsol_host'))->group(function () {
    Route::get('/', fn () => view('repsol-gas'));
    Route::post('/', [GasOrderController::class, 'store']);
    Route::get('/repsol-gas', fn () => redirect('/', 301));
    Route::any('{caminho}', fn () => redirect()->away('https://www.gocarmat.pt'.request()->getRequestUri(), 301))
        ->where('caminho', '.*');
});

// Estas páginas são geridas no backoffice (composer de blocos). Cada rota indica
// a view original como alternativa, caso a página ainda não exista na BD.
Route::get('/', fn () => app(PageController::class)->show('home', 'home'))->name('home');
Route::get('/sobre-nos', fn () => app(PageController::class)->show('sobre-nos', 'about'))->name('about');
Route::get('/servicos', fn () => app(PageController::class)->show('servicos', 'services'))->name('services');
Route::get('/eva-powerlab', fn () => app(PageController::class)->show('eva-powerlab', 'eva'))->name('eva');
Route::get('/marcacoes', fn () => app(PageController::class)->show('marcacoes', 'bookings.create'))->name('marcacoes');
Route::redirect('/contactos', '/marcacoes', 301);
Route::post('/marcacoes', [BookingController::class, 'store'])->name('marcacoes.store');
Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog-sugestoes', [BlogController::class, 'suggest'])->name('blog.suggest');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::view('/politica-de-privacidade', 'privacy')->name('privacy');
Route::view('/politica-de-cookies', 'cookies')->name('cookies');
Route::view('/termos-e-condicoes', 'terms')->name('terms');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Em pré-produção bloqueia os motores de busca; em produção permite tudo.
Route::get('/robots.txt', function () {
    $conteudo = filled(config('staging.password'))
        ? "User-agent: *\nDisallow: /\n"
        : "User-agent: *\nDisallow: /admin\n\nSitemap: ".url('/sitemap.xml')."\n";

    return response($conteudo, 200, ['Content-Type' => 'text/plain']);
});

// Landing page da parceria Repsol Gás — entrega de bilhas de gás ao domicílio.
// Pedidos ficam guardados à parte (GasOrder), não são marcações de oficina.
// O endereço oficial é o subdomínio (grupo no topo deste ficheiro); no domínio
// principal /repsol-gas redireciona para lá, e em local/staging serve a página.
Route::get('/repsol-gas', function () {
    if (in_array(request()->getHost(), ['gocarmat.pt', 'www.gocarmat.pt'], true)) {
        return redirect()->away('https://'.config('app.repsol_host').'/', 301);
    }

    return view('repsol-gas');
})->name('repsol-gas');
Route::post('/repsol-gas', [GasOrderController::class, 'store'])->name('gas-orders.store');

// Páginas criadas no backoffice e, em último caso, os redirects 301 dos URLs
// antigos do WordPress (ex: /inspecao-automovel -> /blog/inspecao-automovel).
Route::fallback(function (string $any = '') {
    $path = trim(request()->path(), '/');

    $page = Page::published()->where('slug', $path)->first();

    if ($page) {
        return response()->view('pages.show', ['page' => $page]);
    }

    $atual = '/'.$path;

    $redirect = Redirect::where('from_path', $atual)->first();

    // Sem correspondência exata, tenta um redirecionamento "prefixo/*" (ex:
    // /produto/* apanha /produto/qualquer-coisa e tudo o que vier a seguir).
    // A correspondência exata ganha sempre a um prefixo, se ambos existirem.
    if (! $redirect) {
        $redirect = Redirect::where('from_path', 'like', '%/*')
            ->get()
            ->first(function ($r) use ($atual) {
                $prefixo = substr($r->from_path, 0, -2);

                return $atual === $prefixo || str_starts_with($atual, $prefixo.'/');
            });
    }

    if ($redirect) {
        $redirect->increment('hits');

        return redirect($redirect->to_path, 301);
    }

    abort(404);
});
