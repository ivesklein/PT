<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('crons', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('function');
			$table->string('vars');
			$table->datetime('triggertime');
			$table->boolean('fired');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('crons');
	}

}
