<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubjectIdRemoveStudentIdNotasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('notas', function(Blueprint $table)
		{
			$table->dropColumn('student_wc_id');
			$table->string('subject_id')->before('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notas', function(Blueprint $table)
		{
			//
		});
	}

}
