<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgresoCursosTable extends Migration
{
    public function up()
    {
        Schema::create('progreso_cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->boolean('completado')->default(false);
            $table->float('progreso')->default(0);
            $table->timestamps();
            $table->unique(['user_id', 'curso_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('progreso_cursos');
    }
}
