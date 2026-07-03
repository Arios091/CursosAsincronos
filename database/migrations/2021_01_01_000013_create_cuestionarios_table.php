<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuestionariosTable extends Migration
{
    public function up()
    {
        Schema::create('cuestionarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained()->onDelete('cascade');
            $table->string('titulo')->nullable();
            $table->integer('min_aprobacion')->default(100);
            $table->timestamps();
            $table->unique('modulo_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cuestionarios');
    }
}
