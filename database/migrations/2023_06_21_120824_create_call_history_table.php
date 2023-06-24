<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCallHistoryTable extends Migration {

	public function up()
	{
		Schema::create('call_history', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->longText('data')->nullable();
			$table->bigInteger('user_id')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('call_history');
	}
}