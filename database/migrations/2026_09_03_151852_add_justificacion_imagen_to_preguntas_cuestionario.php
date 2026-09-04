<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJustificacionImagenToPreguntasCuestionario extends Migration
{
    public function up()
    {
        Schema::table('preguntas_cuestionario', function (Blueprint $table) {
            // Justificacion: explica por que la respuesta correcta es correcta.
            // Nullable para que los cuestionarios existentes no se vean afectados.
            $table->text('justificacion')->nullable()->after('texto');
            // Imagen opcional por pregunta.
            $table->string('imagen')->nullable()->after('justificacion');
        });
    }

    public function down()
    {
        Schema::table('preguntas_cuestionario', function (Blueprint $table) {
            $table->dropColumn(['justificacion', 'imagen']);
        });
    }
}
