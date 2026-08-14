<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->json('content')->nullable();   // blocos do composer
            $table->string('status')->default('draft'); // draft | published
            $table->timestamp('published_at')->nullable();
            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->string('og_image')->nullable();
            $table->boolean('show_in_menu')->default(false);
            $table->unsignedInteger('menu_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
