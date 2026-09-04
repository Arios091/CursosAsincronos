<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJustificacionImagenToPreguntasExamenFinal extends Migration
{
    public function up()
    {
        Schema::table('preguntas_examen_final', function (Blueprint $table) {
            $table->text('justificacion')->nullable()->after('texto');
            $table->string('imagen')->nullable()->after('justificacion');
        });
    }

    public function down()
    {
        Schema::table('preguntas_examen_final', function (Blueprint $table) {
            $table->dropColumn(['justificacion', 'imagen']);
        });
    }
}
