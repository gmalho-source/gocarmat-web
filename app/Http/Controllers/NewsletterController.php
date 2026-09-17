<?php

namespace App\Http\Controllers;

use App\Services\Brevo;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request, Brevo $brevo)
    {
        // Honeypot: campo invisível que só os robôs preenchem
        if ($request->filled('website')) {
            return back()->with('newsletter', 'ok');
        }

        $dados = $request->validate([
            'email' => ['required', 'email', 'max:190'],
        ], [
            'email.required' => 'Indique o seu e-mail.',
            'email.email' => 'Indique um e-mail válido.',
        ]);

        $brevo->subscrever($dados['email'], origem: 'footer');

        return back()->with('newsletter', 'ok');
    }
}
