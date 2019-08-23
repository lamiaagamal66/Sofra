<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRestaurantsTable extends Migration {

	public function up()
	{
		Schema::create('restaurants', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('email');
			$table->string('mobile');
			$table->string('password');
			$table->integer('region_id');
			$table->decimal('minimum_cost', 8,2);
			$table->decimal('delivery_cost', 8,2);
			$table->string('whatsapp');
			$table->string('image');
			$table->string('api_token', 60);
			$table->boolean('is_active')->default(1);
			$table->enum('status', array('open', 'closed'));
			$table->string('pin_code', 6)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('restaurants');
	}
}