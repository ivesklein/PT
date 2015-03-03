<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('actions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('who');//quien hace la acción
			$table->string('what');//que es lo que hace
			$table->string('where');//donde o a quien, ids
			$table->string('related_to');//que es eso del where, tema, usuario, tearea
			$table->string('data');//datos de lo que hace
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
		Schema::drop('actions');
	}

}
