<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('internal');
            $table->string('slug')->unique();
            $table->string('status')->default('draft');
            $table->boolean('featured')->default(false);
            $table->string('cover_image_url')->nullable();
            $table->string('external_url')->nullable();
            $table->boolean('share_enabled')->default(true);
            $table->json('title');
            $table->json('excerpt');
            $table->json('content')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
