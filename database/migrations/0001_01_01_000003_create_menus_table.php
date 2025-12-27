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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->string('nombre');
            $table->string('url')->nullable();
            $table->string('icono')->nullable();
            $table->integer('nivel')->default(0);
            $table->foreignId('id_padre')->nullable()->constrained('menus')->onDelete('cascade');
            $table->integer('orden')->default(0);
            $table->boolean('es_colapsible')->default(false);
            $table->timestamps();
            $table->index('id_empresa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
