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
        Schema::table('phva_matrices', function (Blueprint $table) {
            $table->string('phase')->default('planear')->after('phva_year_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phva_matrices', function (Blueprint $table) {
            $table->dropColumn('phase');
        });
    }
};
