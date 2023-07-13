<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEngTestTable extends Migration {

	public function up()
	{
		Schema::create('eng_test', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('title', 225)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('eng_test');
	}
}