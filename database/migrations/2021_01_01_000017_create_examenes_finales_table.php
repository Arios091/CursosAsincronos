<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamenesFinalesTable extends Migration
{
    public function up()
    {
        Schema::create('examenes_finales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->string('titulo')->default('Examen Final');
            $table->integer('min_aprobacion')->default(80);
            $table->timestamps();
            $table->unique('curso_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('examenes_finales');
    }
}
