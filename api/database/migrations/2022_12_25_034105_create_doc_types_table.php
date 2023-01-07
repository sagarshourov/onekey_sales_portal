<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocTypesTable extends Migration {

	public function up()
	{
		Schema::create('doc_types', function(Blueprint $table) {
			$table->increments('id');
			$table->string('title', 225)->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('doc_types');
	}
}