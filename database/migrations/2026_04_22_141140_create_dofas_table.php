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
        Schema::create('dofas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phva_year_id')->constrained()->onDelete('cascade');
            $table->string('proceso');
            $table->string('responsable');
            $table->enum('factor', ['interno', 'externo']);
            $table->string('tipo'); // debilidad, fortaleza, oportunidad, amenaza
            $table->text('descripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dofas');
    }
};
