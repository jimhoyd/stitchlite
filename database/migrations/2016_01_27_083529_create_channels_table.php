<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();    
            $table->string('sync');
            $table->timestamps();
        });
        
        Schema::create('channel_product', function (Blueprint $table) {
        	$table->integer('channel_id')->unsigned()->index();
        	$table->integer('product_id')->unsigned()->index();
        	$table->integer('reference_id')->unsigned()->index();
        	$table->integer('quantity')->nullable();
        	$table->float('price')->nullable();
        	$table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');        	 
        	$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        	$table->timestamps();        	
        	$table->unique(['channel_id', 'product_id']);
        }); 
                
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::drop('channel_product');
        Schema::drop('channels');
    }
}
