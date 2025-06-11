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
        Schema::create('theme_theme_color', function (Blueprint $table) {
            $table->foreignId('theme_id')->constrained()->onDelete('cascade');
            $table->foreignId('theme_color_id')->constrained()->onDelete('cascade');
            $table->primary(['theme_id', 'theme_color_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_theme_color');
    }
};
