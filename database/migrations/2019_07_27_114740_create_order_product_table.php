<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderProductTable extends Migration {

	public function up()
	{
		Schema::create('order_product', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('order_id');
			$table->integer('product_id');
			$table->decimal('salary', 8,2);
			$table->integer('quantity');
			$table->text('special_order');
		});
	}

	public function down()
	{
		Schema::drop('order_product');
	}
}