<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotiTypeTable extends Migration {

	public function up()
	{
		Schema::create('noti_type', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('title', 225);
		});
	}

	public function down()
	{
		Schema::drop('noti_type');
	}
}