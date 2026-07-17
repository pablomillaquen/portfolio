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
        Schema::table('projects', function (Blueprint $table) {
            $table->json('problem')->nullable()->after('description');
            $table->json('approach')->nullable()->after('problem');
            $table->json('contribution')->nullable()->after('approach');
            $table->json('what_it_demonstrates')->nullable()->after('contribution');
            $table->string('project_status')->default('active')->after('stack');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'problem',
                'approach',
                'contribution',
                'what_it_demonstrates',
                'project_status',
            ]);
        });
    }
};
