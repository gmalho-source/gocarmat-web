<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->renameColumn('mailchimp_status', 'brevo_status');
            $table->renameColumn('mailchimp_error', 'brevo_error');
        });
    }

    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->renameColumn('brevo_status', 'mailchimp_status');
            $table->renameColumn('brevo_error', 'mailchimp_error');
        });
    }
};
