<?php

class ExampleTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBasicExample()
	{
		//$crawler = $this->client->request('GET', '/');

		//$this->assertTrue($this->client->getResponse()->isOk());
		
		CSV::test();
		CronHelper::test();
		CronRoute::test();
		GetFile::test();
		Menu::test();
		Pendientes::test();

		PostComision::data();
		PostEventos::nuevo();
		PostHojaRuta::estado();
		PostMemorias::crear();
		PostPeriodos::crear();
		PostReportes::rezagados();
		PostTareas::guardar();
		PostTextos::gettextos();
		PostUsuarios::agregar();
		PostWC::ajxvernota();
		PostWebcursos::cursos();
		Rol::test();
		UserCreation::test();

		ViewsCron::test();
		ViewsEntregas::test();
		ViewsFirst::test();
		ViewsHojaRuta::test();
		ViewsReg::test();
		ViewsReportes::test();
		ViewsTexto::test();
		ViewsTypeahead::test();
		ViewsUsers::test();
		ViewsWC::test();
		ViewsWebcursos::test();

	}

}
