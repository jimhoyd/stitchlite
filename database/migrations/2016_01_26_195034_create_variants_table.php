<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('sku', 60)->unique()->index();
            $table->string('parent_sku');
            $table->integer('quantity');
            $table->float('price');
            $table->timestamps();
            
            $table->foreign('parent_sku')->references('sku')->on('products')->onDelete('cascade');            
        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('variants');
    }
}
