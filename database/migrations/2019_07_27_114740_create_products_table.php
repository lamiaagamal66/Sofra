<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	public function up()
	{
		Schema::create('products', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('image');
			$table->decimal('salary', 8,2);
			$table->decimal('discount', 8,2);
			$table->string('prepare_time');
			$table->text('description');
			$table->integer('restaurant_id');
		});
	}

	public function down()
	{
		Schema::drop('products');
	}
}