<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('UserTableSeeder');
		$a = new Texto;
		$a->texto = "declaracion-alumno";
		$a->parrafo = "(poblar)";
		$a->save();

		$a = new Texto;
		$a->texto = "declaracion-profesor";
		$a->parrafo = "(poblar)";
		$a->save();

		$a = new Texto;
		$a->texto = "declaracion-revisor";
		$a->parrafo = "(poblar)";
		$a->save();

		$a = new Texto;
		$a->texto = "declaracion-secretaria";
		$a->parrafo = "(poblar)";
		$a->save();
	}

}
