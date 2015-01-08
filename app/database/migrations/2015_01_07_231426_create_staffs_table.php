<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('staffs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('pm_id');
			$table->string('wc_id')->unique();
			$table->string('name');
			$table->string('surname');
			$table->string('password');
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
		Schema::drop('staffs');
	}

}
