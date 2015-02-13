<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWcUidStaffsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('staffs', function(Blueprint $table)
		{
			$table->string('wc_uid',20)->after('wc_id');;
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('staffs', function(Blueprint $table)
		{
			//
		});
	}

}
