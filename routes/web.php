<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Models\Page;
use App\Models\Redirect;
use Illuminate\Support\Facades\Route;

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
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::view('/politica-de-privacidade', 'privacy')->name('privacy');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Em pré-produção bloqueia os motores de busca; em produção permite tudo.
Route::get('/robots.txt', function () {
    $conteudo = filled(config('staging.password'))
        ? "User-agent: *\nDisallow: /\n"
        : "User-agent: *\nDisallow: /admin\n\nSitemap: ".url('/sitemap.xml')."\n";

    return response($conteudo, 200, ['Content-Type' => 'text/plain']);
});

// Páginas criadas no backoffice e, em último caso, os redirects 301 dos URLs
// antigos do WordPress (ex: /inspecao-automovel -> /blog/inspecao-automovel).
Route::fallback(function (string $any = '') {
    $path = trim(request()->path(), '/');

    $page = Page::published()->where('slug', $path)->first();

    if ($page) {
        return response()->view('pages.show', ['page' => $page]);
    }

    $redirect = Redirect::where('from_path', '/'.$path)->first();

    if ($redirect) {
        $redirect->increment('hits');

        return redirect($redirect->to_path, 301);
    }

    abort(404);
});
