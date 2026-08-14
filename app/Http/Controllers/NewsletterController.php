<?php

namespace App\Http\Controllers;

use App\Services\Mailchimp;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request, Mailchimp $mailchimp)
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

        $mailchimp->subscrever($dados['email'], origem: 'footer');

        return back()->with('newsletter', 'ok');
    }
}
