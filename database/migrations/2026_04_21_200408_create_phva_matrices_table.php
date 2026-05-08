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
        Schema::create('phva_matrices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phva_year_id')->constrained('phva_years')->onDelete('cascade');
            $table->string('name');
            $table->string('file_path');
            $table->string('extension');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phva_matrices');
    }
};
