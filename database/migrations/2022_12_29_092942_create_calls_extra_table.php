<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCallsExtraTable extends Migration {

	public function up()
	{
		Schema::create('calls_extra', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('call_id')->nullable();
			$table->string('field', 225)->nullable();
			$table->longText('value')->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('calls_extra');
	}
}