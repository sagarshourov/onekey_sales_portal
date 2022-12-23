<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCancelReasonTable extends Migration {

	public function up()
	{
		Schema::create('cancel_reason', function(Blueprint $table) {
			$table->increments('id');
			$table->string('title', 225);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('cancel_reason');
	}
}