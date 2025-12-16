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
            // Change enum to string to accept Title Case values like 'Sanaviyya Ulya'
            $table->string('level')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Revert back to enum
            $table->enum('level', ['sanaviyya_ulya', 'bakalooriyya', 'majestar'])->nullable()->change();
        });
    }
};
