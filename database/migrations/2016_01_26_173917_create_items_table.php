<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable()->unsigned()->index();
            $table->string('sku', 60)->nullable()->unique()->index();
            $table->string('name');
            $table->string('summary', 1024)->nullable();
            $table->string('description', 2048)->nullable();
            $table->integer('quantity')->nullable();
            $table->float('price')->nullable();
            $table->float('weight')->nullable();
            $table->timestamps();
        });

        Schema::create('item_media', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->string('uri', 1024);
            $table->string('type', 60);
            $table->timestamps();
        });

        Schema::create('item_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->string('key', 60);
            $table->string('value', 60);
            $table->string('type', 60);
            $table->timestamps();
        });

        Schema::create('item_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->string('tag', 60);
            $table->string('type', 60);
            $table->timestamps();
        });

        Schema::create('item_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->string('tag', 60);
            $table->string('type', 60);
            $table->timestamps();
        });

        Schema::create('item_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->string('tag', 60);
            $table->string('type', 60);
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
        Schema::drop('items');
    }
}
