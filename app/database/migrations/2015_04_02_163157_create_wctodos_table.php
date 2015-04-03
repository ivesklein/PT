<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWctodosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wctodos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('did');
			$table->string('action');
			$table->string('data',512);
			$table->string('response',512);
			$table->string('periodo');
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
		Schema::drop('wctodos');
	}

}
