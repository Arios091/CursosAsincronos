<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultadoCuestionariosTable extends Migration
{
    public function up()
    {
        Schema::create('resultado_cuestionarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materiales')->onDelete('cascade');
            $table->integer('intentos')->default(0);
            $table->integer('puntaje')->default(0);
            $table->boolean('aprobado')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'material_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('resultado_cuestionarios');
    }
}
