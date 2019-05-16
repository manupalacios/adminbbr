<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLdmnivelToLiqdelmesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liqdelmes', function (Blueprint $table) {
            $table->unsignedSmallInteger('LDMNivel')->nullable(false)->default(0)->after('LDMNro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liqdelmes', function (Blueprint $table) {
            $table->dropColumn('LDMNivel');
        });
    }
}
