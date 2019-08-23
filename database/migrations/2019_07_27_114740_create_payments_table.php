<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration {

	public function up()
	{
		Schema::create('payments', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->decimal('amount', 8,2);
			$table->text('note');
			$table->integer('restaurant_id');
			$table->datetime('payment_date');
		});
	}

	public function down()
	{
		Schema::drop('payments');
	}
}