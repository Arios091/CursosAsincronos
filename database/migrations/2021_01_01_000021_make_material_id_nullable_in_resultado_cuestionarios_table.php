<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeMaterialIdNullableInResultadoCuestionariosTable extends Migration
{
    public function up()
    {
        Schema::table('resultado_cuestionarios', function (Blueprint $table) {
            $table->foreignId('material_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('resultado_cuestionarios', function (Blueprint $table) {
            $table->foreignId('material_id')->nullable(false)->change();
        });
    }
}
