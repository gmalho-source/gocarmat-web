<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $posts = Post::published()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%")
                        ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('published_at')
            ->paginate(13)
            ->withQueryString();

        return view('blog.index', compact('posts', 'search'));
    }

    /** Sugestões de pesquisa para o autocomplete do campo de pesquisa do blog. */
    public function suggest(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        if (mb_strlen($search) < 2) {
            return response()->json([]);
        }

        $sugestoes = Post::published()
            ->where('title', 'like', "%{$search}%")
            ->orderByDesc('published_at')
            ->take(6)
            ->get(['title', 'slug'])
            ->map(fn (Post $post) => [
                'title' => $post->title,
                'url' => route('blog.show', $post->slug),
            ]);

        return response()->json($sugestoes);
    }

    public function show(string $slug)
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $post->categories->pluck('id')))
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        if ($related->count() < 3) {
            $related = $related->concat(
                Post::published()
                    ->where('id', '!=', $post->id)
                    ->whereNotIn('id', $related->pluck('id'))
                    ->orderByDesc('published_at')
                    ->take(3 - $related->count())
                    ->get()
            );
        }

        return view('blog.show', compact('post', 'related'));
    }
}
