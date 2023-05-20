<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivityTable extends Migration {

	public function up()
	{
		Schema::create('activity', function(Blueprint $table) {
			$table->increments('id');
			$table->string('subject', 225)->nullable();
			$table->string('url', 225)->nullable();
			$table->string('method', 225)->nullable();
			$table->string('ip', 20)->nullable();
			$table->string('agent', 225)->nullable();
			$table->bigInteger('user_id')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('activity');
	}
}