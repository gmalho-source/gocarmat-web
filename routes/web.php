<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Models\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/sobre-nos', 'about')->name('about');
Route::view('/servicos', 'services')->name('services');
Route::view('/eva-powerlab', 'eva')->name('eva');
Route::get('/marcacoes', [BookingController::class, 'create'])->name('marcacoes');
Route::post('/marcacoes', [BookingController::class, 'store'])->name('marcacoes.store');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::view('/politica-de-privacidade', 'privacy')->name('privacy');

// Redirects 301 dos URLs antigos do WordPress (ex: /inspecao-automovel -> /blog/inspecao-automovel)
Route::fallback(function (string $any = '') {
    $path = '/'.trim(request()->path(), '/');

    $redirect = Redirect::where('from_path', $path)->first();

    if ($redirect) {
        $redirect->increment('hits');

        return redirect($redirect->to_path, 301);
    }

    abort(404);
});
