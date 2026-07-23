<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'offices' => Office::active()->get(),
            'posts' => Post::published()->orderByDesc('published_at')->take(4)->get(),
        ]);
    }
}
