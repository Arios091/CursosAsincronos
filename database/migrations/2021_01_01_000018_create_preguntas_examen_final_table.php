<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreguntasExamenFinalTable extends Migration
{
    public function up()
    {
        Schema::create('preguntas_examen_final', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examen_final_id')->constrained('examenes_finales')->onDelete('cascade');
            $table->text('texto');
            $table->integer('orden');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('preguntas_examen_final');
    }
}
