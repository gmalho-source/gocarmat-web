<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        // Em pré-produção não faz sentido oferecer sitemap: o site não deve
        // sequer ser indexado.
        abort_if(filled(config('staging.password')), 404);

        $urls = [];

        foreach (Page::published()->get() as $page) {
            $urls[] = [
                'loc' => $page->slug === 'home' ? url('/') : url('/'.$page->slug),
                'lastmod' => $page->updated_at,
                'priority' => $page->slug === 'home' ? '1.0' : '0.8',
            ];
        }

        $urls[] = ['loc' => route('blog.index'), 'lastmod' => Post::published()->max('updated_at'), 'priority' => '0.7'];

        foreach (Post::published()->orderByDesc('published_at')->get() as $post) {
            $urls[] = [
                'loc' => route('blog.show', $post->slug),
                'lastmod' => $post->updated_at,
                'priority' => '0.6',
            ];
        }

        $urls[] = ['loc' => url('/politica-de-privacidade'), 'lastmod' => null, 'priority' => '0.3'];

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
