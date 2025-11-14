<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['A', 'B', 'C', 'D']);
            $table->enum('type', ['individual', 'group', 'general']);
            $table->enum('stream', ['sharia', 'sharia_plus', 'she', 'she_plus', 'life', 'life_plus', 'bayyinath', 'general']);
            $table->enum('level', ['sanaviyya_ulya', 'bakalooriyya', 'majestar'])->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
