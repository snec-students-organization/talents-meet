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
        Schema::table('events', function (Blueprint $table) {
            $table->string('level')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Reverting to not null theoretically, but previous state was ambiguous. 
            // We'll leave it nullable in down or attempt to revert if needed, 
            // but for safety in dev usually we just want up to work.
            $table->string('level')->nullable(false)->change();
        });
    }
};
