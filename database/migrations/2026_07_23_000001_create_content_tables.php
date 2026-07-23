<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->string('featured_image')->nullable();
            $table->string('status')->default('draft'); // draft | published
            $table->timestamp('published_at')->nullable();
            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->string('og_image')->nullable();
            // Origem (migração WordPress)
            $table->unsignedBigInteger('wp_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('category_post', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'post_id']);
        });

        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('schedule'); // ex: "Seg-Sex: 08:30 - 18:00"
            $table->string('phones');   // ex: "925 410 248 / 910 684 941"
            $table->string('phone_note')->nullable(); // ex: "Chamada para a rede móvel nacional"
            $table->string('email');
            $table->string('maps_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('service')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('newsletter_opt_in')->default(false);
            $table->string('source')->default('marcacoes'); // marcacoes | checkup | eva
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_path')->unique(); // ex: /inspecao-automovel
            $table->string('to_path');             // ex: /blog/inspecao-automovel
            $table->unsignedInteger('hits')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('offices');
        Schema::dropIfExists('category_post');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('categories');
    }
};
