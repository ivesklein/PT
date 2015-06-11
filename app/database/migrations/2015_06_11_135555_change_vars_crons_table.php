<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeVarsCronsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement('ALTER TABLE crons MODIFY COLUMN vars LONGTEXT');
	}

	public function down()
	{
	    DB::statement('ALTER TABLE crons MODIFY COLUMN vars VARCHAR(255)');
	}

}
