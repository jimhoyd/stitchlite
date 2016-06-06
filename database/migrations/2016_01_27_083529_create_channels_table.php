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
        
        Schema::create('channel_item', function (Blueprint $table) {
        	$table->integer('channel_id')->unsigned()->index();
        	$table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');        	 
        	$table->integer('item_id')->unsigned()->index();
        	$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        	$table->timestamps();        	
        	$table->unique(['channel_id', 'item_id']);
        }); 
                
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::drop('channel_item');
        Schema::drop('channels');
    }
}
