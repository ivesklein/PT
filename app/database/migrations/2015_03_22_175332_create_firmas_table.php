<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirmasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('firmas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('subject_id');
			$table->string('student1');
			$table->string('student2');
			$table->string('adviser');
			$table->string('revisor');
			$table->string('secre1');
			$table->string('secre2');
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
		Schema::drop('firmas');
	}

}
