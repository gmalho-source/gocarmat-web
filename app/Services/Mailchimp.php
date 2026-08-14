<?php

namespace App\Services;

use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Subscrição de contactos na audiência Mailchimp.
 *
 * A chave e o ID da lista vêm das Definições do backoffice, para poderem ser
 * mudados sem deploy. Se a API falhar (ou não estiver configurada), o contacto
 * fica guardado localmente na mesma — o registo nunca se perde.
 */
class Mailchimp
{
    public function configurado(): bool
    {
        return filled(Setting::get('mailchimp_api_key')) && filled(Setting::get('mailchimp_list_id'));
    }

    /** Guarda o subscritor e tenta enviá-lo para o Mailchimp. */
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
            $chave = Setting::get('mailchimp_api_key');
            $lista = Setting::get('mailchimp_list_id');

            // O datacenter faz parte da chave (ex: abc123-us14)
            $datacenter = str_contains($chave, '-') ? last(explode('-', $chave)) : null;

            if (! $datacenter) {
                throw new \RuntimeException('A chave da API não inclui o datacenter (deve terminar em -usX).');
            }

            $resposta = Http::withBasicAuth('anystring', $chave)
                ->timeout(15)
                ->put("https://{$datacenter}.api.mailchimp.com/3.0/lists/{$lista}/members/".md5(mb_strtolower($email)), [
                    'email_address' => $email,
                    'status_if_new' => 'subscribed',
                    'merge_fields' => array_filter(['FNAME' => $nome]),
                ]);

            if ($resposta->successful()) {
                $subscritor->update([
                    'mailchimp_status' => 'subscribed',
                    'mailchimp_error' => null,
                    'synced_at' => now(),
                ]);
            } else {
                $subscritor->update([
                    'mailchimp_status' => 'failed',
                    'mailchimp_error' => $resposta->json('detail') ?: 'HTTP '.$resposta->status(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Mailchimp: falha ao subscrever '.$email.' — '.$e->getMessage());
            $subscritor->update([
                'mailchimp_status' => 'failed',
                'mailchimp_error' => $e->getMessage(),
            ]);
        }

        return $subscritor;
    }
}
