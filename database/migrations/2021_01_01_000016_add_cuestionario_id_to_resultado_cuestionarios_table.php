<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCuestionarioIdToResultadoCuestionariosTable extends Migration
{
    public function up()
    {
        Schema::table('resultado_cuestionarios', function (Blueprint $table) {
            $table->foreignId('cuestionario_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('resultado_cuestionarios', function (Blueprint $table) {
            $table->dropForeign(['cuestionario_id']);
            $table->dropColumn('cuestionario_id');
        });
    }
}
