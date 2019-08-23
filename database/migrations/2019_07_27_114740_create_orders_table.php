<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	public function up()
	{ 
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->text('note');
			$table->text('address');
			$table->integer('payment_type_id');
			$table->decimal('cost', 8,2);
			$table->decimal('delivery_cost', 8,2);
			$table->decimal('total_cost', 8,2);
			$table->integer('client_id');
			$table->integer('restaurant_id');
			$table->enum('status', array('pending', 'accepted', 'rejected', 'delivered', 'declined'))->default('pending');
			$table->decimal('commission', 8,2);
		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}