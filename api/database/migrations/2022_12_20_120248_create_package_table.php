<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePackageTable extends Migration {

	public function up()
	{
		Schema::create('package', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('title')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('package');
	}
}