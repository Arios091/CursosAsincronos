<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcionesExamenFinalTable extends Migration
{
    public function up()
    {
        Schema::create('opciones_examen_final', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregunta_id')->constrained('preguntas_examen_final')->onDelete('cascade');
            $table->text('texto');
            $table->boolean('es_correcta')->default(false);
            $table->integer('orden');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('opciones_examen_final');
    }
}
