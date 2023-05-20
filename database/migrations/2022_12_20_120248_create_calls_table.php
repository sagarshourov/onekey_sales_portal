<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCallsTable extends Migration {

	public function up()
	{
		Schema::create('calls', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('first_name', 225)->nullable();
			$table->string('last_name', 225)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->string('phone_numbr', 225)->nullable();
			$table->string('email', 225)->nullable();
			$table->string('follow_up_date', 10)->nullable();
			$table->string('status', 10)->nullable();
			$table->boolean('ag')->default(0);
			$table->integer('package')->nullable();
			$table->string('last_cntact', 10)->nullable();
			$table->string('age', 5)->nullable();
			$table->string('gpa', 5)->nullable();
			$table->string('last_status_date', 10)->nullable();
			$table->text('last_status_notes')->nullable();
			$table->integer('results')->nullable();
			$table->integer('cancel_reason')->nullable();
			$table->text('feedbacks')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('calls');
	}
}