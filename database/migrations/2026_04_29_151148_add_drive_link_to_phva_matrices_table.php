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
            $table->string('drive_link')->nullable()->after('name');
            $table->string('file_path')->nullable()->change();
            $table->string('extension')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phva_matrices', function (Blueprint $table) {
            $table->dropColumn('drive_link');
            $table->string('file_path')->nullable(false)->change();
            $table->string('extension')->nullable(false)->change();
        });
    }
};
