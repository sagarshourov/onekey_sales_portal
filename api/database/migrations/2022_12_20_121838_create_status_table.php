<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatusTable extends Migration {

	public function up()
	{
		Schema::create('status', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('title', 225)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('status');
	}
}