<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcionesCuestionarioTable extends Migration
{
    public function up()
    {
        Schema::create('opciones_cuestionario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materiales')->onDelete('cascade');
            $table->text('texto');
            $table->boolean('es_correcta')->default(false);
            $table->integer('orden');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('opciones_cuestionario');
    }
}
