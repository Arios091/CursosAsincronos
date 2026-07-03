<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultadosExamenFinalTable extends Migration
{
    public function up()
    {
        Schema::create('resultados_examen_final', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('examen_final_id')->constrained('examenes_finales')->onDelete('cascade');
            $table->integer('intentos')->default(0);
            $table->integer('puntaje')->default(0);
            $table->boolean('aprobado')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resultados_examen_final');
    }
}
