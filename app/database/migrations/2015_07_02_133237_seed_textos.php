<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedTextos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$a = new Texto;
		$a->texto = "declaracion-alumno";
		$a->save();

		$a = new Texto;
		$a->texto = "declaracion-profesor";
		$a->save();

		$a = new Texto;
		$a->texto = "declaracion-revisor";
		$a->save();

		$a = new Texto;
		$a->texto = "declaracion-secretaria";
		$a->save();


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
