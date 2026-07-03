<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreguntasCuestionarioTable extends Migration
{
    public function up()
    {
        Schema::create('preguntas_cuestionario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuestionario_id')->constrained()->onDelete('cascade');
            $table->text('texto');
            $table->integer('orden');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('preguntas_cuestionario');
    }
}
