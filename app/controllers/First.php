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
		return View::make('nav2');
	}

	public function getDashboard()
	{
		return View::make('dashboard');
	}

	public function getLogin()
	{
		return View::make('login.login');
	}

}

?>