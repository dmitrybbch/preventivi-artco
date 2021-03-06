<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nomeTavolo')->nullable();
            $table->string('stato')->default('libero');

            $table->string('noteAggiuntive')->nullable();
            $table->float('ricarico')->nullable();
            $table->integer('client_id')->unsigned()->nullable();
            $table->string('creatoDa')->nullable();
            $table->date('creatoInData')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables');
    }
}
