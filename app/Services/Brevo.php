<?php

namespace App\Services;

use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Subscrição de contactos na lista Brevo (ex-Sendinblue).
 *
 * A chave e o ID da lista vêm das Definições do backoffice, para poderem ser
 * mudados sem deploy. Se a API falhar (ou não estiver configurada), o contacto
 * fica guardado localmente na mesma — o registo nunca se perde.
 */
class Brevo
{
    public function configurado(): bool
    {
        return filled(Setting::get('brevo_api_key')) && filled(Setting::get('brevo_list_id'));
    }

    /** Guarda o subscritor e tenta enviá-lo para o Brevo. */
    public function subscrever(string $email, ?string $nome = null, string $origem = 'footer'): NewsletterSubscriber
    {
        $subscritor = NewsletterSubscriber::updateOrCreate(
            ['email' => mb_strtolower(trim($email))],
            ['name' => $nome, 'source' => $origem],
        );

        if (! $this->configurado()) {
            return $subscritor;
        }

        try {
            $chave = Setting::get('brevo_api_key');
            $lista = (int) Setting::get('brevo_list_id');

            $resposta = Http::withHeaders([
                'api-key' => $chave,
                'accept' => 'application/json',
            ])
                ->timeout(15)
                ->post('https://api.brevo.com/v3/contacts', [
                    'email' => $email,
                    // NOME é o atributo de primeiro nome configurado nesta conta
                    // Brevo (não o "FIRSTNAME" por omissão de outras contas).
                    // (object) força {} em vez de [] no JSON quando não há nome —
                    // a API do Brevo rejeita "attributes" como array vazio.
                    'attributes' => (object) array_filter(['NOME' => $nome]),
                    'listIds' => [$lista],
                    'updateEnabled' => true,
                ]);

            if ($resposta->successful()) {
                $subscritor->update([
                    'brevo_status' => 'subscribed',
                    'brevo_error' => null,
                    'synced_at' => now(),
                ]);
            } else {
                $subscritor->update([
                    'brevo_status' => 'failed',
                    'brevo_error' => $resposta->json('message') ?: 'HTTP '.$resposta->status(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Brevo: falha ao subscrever '.$email.' — '.$e->getMessage());
            $subscritor->update([
                'brevo_status' => 'failed',
                'brevo_error' => $e->getMessage(),
            ]);
        }

        return $subscritor;
    }
}
