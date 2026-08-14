<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Mail\BookingNotification;
use App\Models\Booking;
use App\Models\Office;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    /** Opções do campo Serviço no formulário. */
    public const SERVICES = [
        'Revisão Oficial',
        'Inspeção Automóvel',
        'Pneus',
        'Colisão e Pintura',
        'Mudança de Óleos e Filtros',
        'Climatização Automóvel',
        'EVA Powerlab (elétricos e híbridos)',
        'Check-up Gratuito',
        'Outro assunto',
    ];

    public function create()
    {
        return view('bookings.create', [
            'services' => self::SERVICES,
            'offices' => Office::active()->get(),
        ]);
    }

    public function store(Request $request)
    {
        // Honeypot anti-spam: campo invisível que humanos não preenchem
        if ($request->filled('website')) {
            return redirect()->route('marcacoes')->with('success', true);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['required', 'string', 'max:30'],
            'service' => ['required', 'string', 'in:'.implode(',', self::SERVICES)],
            'notes' => ['nullable', 'string', 'max:2000'],
            'newsletter_opt_in' => ['nullable', 'boolean'],
            'privacy' => ['accepted'],
        ], [
            'privacy.accepted' => 'É necessário aceitar a Política de Privacidade.',
        ]);

        $booking = Booking::create([
            'name' => $data['name'],
            'company' => $data['company'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'],
            'service' => $data['service'],
            'notes' => $data['notes'] ?? null,
            'newsletter_opt_in' => (bool) ($data['newsletter_opt_in'] ?? false),
            'source' => 'marcacoes',
        ]);

        if ($booking->newsletter_opt_in) {
            app(\App\Services\Mailchimp::class)->subscrever($booking->email, $booking->name, 'marcacoes');
        }

        $adminEmail = Setting::get('notification_email', 'apoiocliente@gocarmat.pt');

        try {
            Mail::to($adminEmail)->send(new BookingNotification($booking));
            Mail::to($booking->email)->send(new BookingConfirmation($booking));
        } catch (\Throwable $e) {
            report($e); // o pedido fica sempre guardado em BD, mesmo que o email falhe
        }

        return redirect()->route('marcacoes')->with('success', true);
    }
}
