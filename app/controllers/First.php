<?php

//

class First extends BaseController
{
	
	public function getIndex()
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

}

?>