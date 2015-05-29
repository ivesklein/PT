<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubjectIdSponsorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sponsors', function(Blueprint $table)
		{
			$table->string('subject_id');
			$table->string('puesto');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sponsors', function(Blueprint $table)
		{
			//
		});
	}

}
