<?php

class TextoTableSeeder extends Seeder {

	public function run()
	{
		Texto::create(array("texto"=>'declaracion-alumno'));
		Texto::create(array("texto"=>'declaracion-profesor'));
		Texto::create(array("texto"=>'declaracion-revisor'));
		Texto::create(array("texto"=>'declaracion-secretaria'));
	}











}