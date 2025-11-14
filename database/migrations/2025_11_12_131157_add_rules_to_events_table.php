<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->json('allowed_streams')->nullable(); // Array of allowed streams
            $table->integer('max_participants')->nullable(); // For group events
            $table->integer('max_institution_entries')->nullable(); // Limit per institution
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['allowed_streams', 'max_participants', 'max_institution_entries']);
        });
    }
};
