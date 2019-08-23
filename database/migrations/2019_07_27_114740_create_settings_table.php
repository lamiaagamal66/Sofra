<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	public function up()
	{
		Schema::create('settings', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->text('about_app');
			$table->decimal('commision', 8,2);
			$table->text('commision_msg');
			$table->text('bank_msg');
		});
	}

	public function down()
	{
		Schema::drop('settings');
	}
}