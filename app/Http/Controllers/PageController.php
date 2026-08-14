<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    /**
     * Mostra uma página gerida no backoffice.
     *
     * Se a página ainda não existir na base de dados (ex: instalação nova antes
     * de correr gocarmat:seed-pages), recorre à view original — assim o site
     * nunca fica em branco por causa de conteúdo em falta.
     */
    public function show(string $slug, string $viewAlternativa): View
    {
        $page = Page::published()->where('slug', $slug)->first();

        if ($page) {
            return view('pages.show', ['page' => $page]);
        }

        return view($viewAlternativa, $this->dadosDaViewAntiga($viewAlternativa));
    }

    /** As views originais da Home e das Marcações precisam de dados extra. */
    private function dadosDaViewAntiga(string $view): array
    {
        return match ($view) {
            'home' => [
                'offices' => Office::active()->get(),
                'posts' => Post::published()->orderByDesc('published_at')->take(4)->get(),
            ],
            'bookings.create' => [
                'services' => BookingController::SERVICES,
                'offices' => Office::active()->get(),
            ],
            default => [],
        };
    }
}
