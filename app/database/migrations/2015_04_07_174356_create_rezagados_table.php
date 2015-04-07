<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRezagadosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rezagados', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('student_id');
			$table->string('periodo');
			$table->string('status');
			$table->string('registro');
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
		Schema::drop('rezagados');
	}

}
