<?php

namespace App\Http\Controllers;

use App\Mail\GasOrderConfirmation;
use App\Mail\GasOrderNotification;
use App\Models\GasOrder;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GasOrderController extends Controller
{
    /** Destinatários dos pedidos de gás. */
    public const EMAILS_NOTIFICACAO = ['vasco.sousa@gocarmat.pt', 'repositorio.submissoes@gmail.com'];

    /** Opções do campo Bilha no formulário. */
    public const BILHAS = [
        'Repsol K13',
        'Repsol K11',
        'Repsol K6',
        'Repsol K45',
    ];

    /** Opções do campo Janela de entrega no formulário. */
    public const JANELAS_ENTREGA = [
        '2ª feira: 09h00 - 13h00',
        '2ª feira: 13h00 - 20h00',
        '3ª feira: 09h00 - 13h00',
        '3ª feira: 13h00 - 20h00',
        '4ª feira: 09h00 - 13h00',
        '4ª feira: 13h00 - 20h00',
        '5ª feira: 09h00 - 13h00',
        '5ª feira: 13h00 - 20h00',
        '6ª feira: 09h00 - 13h00',
        '6ª feira: 13h00 - 20h00',
        'Sábado: 09h00 - 15h00',
    ];

    public function store(Request $request)
    {
        // Honeypot anti-spam: campo invisível que humanos não preenchem.
        // Não usa redirect_to aqui (o pedido nunca chega a ser validado) para
        // não abrir um redirecionamento não verificado a um bot.
        if ($request->filled('website')) {
            return redirect()->route('repsol-gas')->with('success', true);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9]+$/'],
            'bilha' => ['required', 'string', 'in:'.implode(',', self::BILHAS)],
            'janela_entrega' => ['required', 'string', 'in:'.implode(',', self::JANELAS_ENTREGA)],
            'privacy' => ['accepted'],
            // Só um caminho interno (ex: "/repsol-gas"), nunca um URL completo
            // — evita que este campo sirva de redireccionamento aberto.
            'redirect_to' => ['nullable', 'string', 'max:255', 'regex:/^\/[^\/].*$|^\/$/'],
        ], [
            'privacy.accepted' => 'É necessário aceitar a Política de Privacidade.',
            'phone.regex' => 'O telefone só pode conter números e o símbolo + para indicativos.',
        ]);

        $order = GasOrder::create([
            'name' => $data['name'],
            'company' => $data['company'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'],
            'bilha' => $data['bilha'],
            'janela_entrega' => $data['janela_entrega'],
        ]);

        try {
            Mail::to(self::EMAILS_NOTIFICACAO)->send(new GasOrderNotification($order));
            Mail::to($order->email)->send(new GasOrderConfirmation($order));
        } catch (\Throwable $e) {
            report($e); // o pedido fica sempre guardado em BD, mesmo que o email falhe
        }

        return redirect($data['redirect_to'] ?? route('repsol-gas'))->with('success', true);
    }
}
