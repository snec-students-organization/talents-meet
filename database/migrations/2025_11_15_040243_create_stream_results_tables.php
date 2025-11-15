<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private $streams = [
        'sharia','sharia_plus','she','she_plus','life','life_plus','bayyinath','general'
    ];

    public function up(): void
    {
        foreach ($this->streams as $stream) {
            Schema::create("{$stream}_results", function (Blueprint $table) use ($stream) {
                $table->id();
                $table->foreignId('institution_id')->constrained('users')->onDelete('cascade');
                $table->integer('total_points')->default(0);
                $table->boolean('confirmed')->default(false);
                $table->timestamps();

                $table->unique(['institution_id'], "{$stream}_results_institution_unique");
            });
        }
    }

    public function down(): void
    {
        foreach ($this->streams as $stream) {
            Schema::dropIfExists("{$stream}_results");
        }
    }
};
