<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('table_id')->unsigned();
            $table->integer('provision_id')->unsigned();
            $table->integer('amount')->unsigned();
            $table->float('add_percent');

            $table->primary(['table_id', 'provision_id']);

            $table->timestamps();
        });

        Schema::table('orders', function($table) {
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->foreign('provision_id')->references('id')->on('provisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
