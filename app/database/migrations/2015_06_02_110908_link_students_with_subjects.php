<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LinkStudentsWithSubjects extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$subjs = Subject::all();
		foreach ($subjs as $subj) {
			$s1 = Student::whereWc_id($subj->student1)->first();
			if(!empty($s1)){
				$s1->subject_id = $subj->id;
				$s1->save();	
			}
			$s2 = Student::whereWc_id($subj->student2)->first();
			if(!empty($s2)){
				$s2->subject_id = $subj->id;
				$s2->save();
			}			
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
