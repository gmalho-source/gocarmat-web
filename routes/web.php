<?php

use App\Http\Controllers\HomeController;
use App\Models\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

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
