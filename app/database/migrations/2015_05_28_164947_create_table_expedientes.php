<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableExpedientes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expedientes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('student_id');
			$table->string('academico');
			$table->string('financiero');
			$table->string('carrera');
			$table->string('titulado');
			$table->string('promedio');
			$table->string('estado');
			$table->string('otros');
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
		Schema::drop('expedientes');
	}

}
