<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgresoMaterialesTable extends Migration
{
    public function up()
    {
        Schema::create('progreso_materiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materiales')->onDelete('cascade');
            $table->boolean('completado')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'material_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('progreso_materiales');
    }
}
