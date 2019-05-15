<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivos', function (Blueprint $table) {
            $table->unsignedInteger('tipo_id')->nullable(false);
            $table->unsignedInteger('grupo_id')->nullable(false);
            $table->unsignedInteger('nivel_id')->nullable(false);
            $table->unsignedInteger('anio')->nullable(false);
            $table->unsignedInteger('mes')->nullable(false);
            $table->unsignedInteger('numero')->nullable(false);
            $table->string('archivo', 150)->nullable(false);

            $table->primary(['tipo_id', 'grupo_id', 'nivel_id', 'anio', 'mes', 'numero']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archivos');
    }
}
