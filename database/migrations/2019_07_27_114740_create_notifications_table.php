<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	public function up()
	{
		Schema::create('notifications', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('title');
			$table->text('content');
			$table->integer('order_id');
			$table->string('notifiable_type');
			$table->integer('notifiable_id');
			$table->enum('action', array('pending', 'accepted', 'rejected', 'delivered', 'Requested'));
		});
	}

	public function down()
	{
		Schema::drop('notifications');
	}
}