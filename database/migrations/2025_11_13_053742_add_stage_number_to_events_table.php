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
        if (!Schema::hasColumn('events', 'stage_number')) {
            $table->unsignedTinyInteger('stage_number')->nullable()->after('stage_type');
        }
    });
}

public function down(): void
{
    Schema::table('events', function (Blueprint $table) {
        $table->dropColumn('stage_number');
    });
}

};
