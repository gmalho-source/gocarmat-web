<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Guardamos sempre o subscritor localmente, mesmo quando o envio para o
        // Mailchimp falha — assim nunca se perde um contacto por causa de uma
        // indisponibilidade da API, e é possível sincronizar mais tarde.
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('source')->default('footer'); // footer | marcacoes
            $table->string('mailchimp_status')->default('pending'); // pending | subscribed | failed
            $table->text('mailchimp_error')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
};
