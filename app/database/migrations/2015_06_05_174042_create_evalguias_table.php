<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvalguiasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('evalguias', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('subject_id');
			$table->string('pg');
			$table->string('promedio');
			$table->string('notas');
			$table->string('comentario');
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
		Schema::drop('evalguias');
	}

}
