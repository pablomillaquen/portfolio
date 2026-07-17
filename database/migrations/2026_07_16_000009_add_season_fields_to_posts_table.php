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
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('season_id')->nullable()->after('content')->constrained()->nullOnDelete();
            $table->integer('episode_number')->nullable()->after('season_id');
            $table->foreignId('related_project_id')->nullable()->after('episode_number')->constrained('projects')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['season_id']);
            $table->dropForeign(['related_project_id']);
            $table->dropColumn(['season_id', 'episode_number', 'related_project_id']);
        });
    }
};
