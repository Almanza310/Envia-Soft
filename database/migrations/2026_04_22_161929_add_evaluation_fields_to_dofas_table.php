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
        Schema::table('dofas', function (Blueprint $blueprint) {
            $blueprint->integer('probabilidad')->nullable()->default(null);
            $blueprint->integer('impacto')->nullable()->default(null);
            $blueprint->string('color')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dofas', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['probabilidad', 'impacto', 'color']);
        });
    }
};
