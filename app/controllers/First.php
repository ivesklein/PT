<?php

//

class First extends BaseController
{
	
	public function getIndex()
	{
		return View::make('index');
		//return "hola";
	}

	public function postIndex()
	{
		return View::make('index');
		//return "hola";
	}

	public function getHeader()
	{
		return View::make('header');
	}

	public function getNav()
	{
		return View::make('nav');
	}

	public function getDashboard()
	{
		return View::make('dashboard');
	}

	public function getLogin()
	{
		return View::make('login.login');
	}

	public function getVista1()
	{
		return View::make('views.view1');
	}
	public function getVista2()
	{
		//digerir csv
		return View::make('views.view1-2');
	}



}

?>