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
			// status
            $table->float('price')->nullable();
			// price_currency
            $table->float('weight')->nullable();
			// weight_units
            $table->timestamps();
        });

        Schema::create('item_media', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->string('uri', 1024);
            $table->string('content_type', 60);
			// file size
			// original name
			// read only
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
            $table->integer('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->integer('location_id')->unsigned()->index();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->string('bin', 60);
            $table->integer('quantity');			
            $table->timestamps();
        });
		
        Schema::create('item_vendors', function (Blueprint $table) {
            $table->integer('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->integer('vendor_id')->unsigned()->index();
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->float('price');
            $table->integer('quantity');
			$table->integer('ordered');
			$table->integer('reorder');
            $table->timestamps();
        });		
		
		// transactions
		// item customers
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
