<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcionesPreguntaTable extends Migration
{
    public function up()
    {
        Schema::create('opciones_pregunta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregunta_id')->constrained('preguntas_cuestionario')->onDelete('cascade');
            $table->text('texto');
            $table->boolean('es_correcta')->default(false);
            $table->integer('orden');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('opciones_pregunta');
    }
}
