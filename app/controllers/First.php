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
		if(isset($_POST['f'])){
			if(method_exists("PostRoute", $_POST['f'])){
				return PostRoute::$_POST['f']();
			}else{
				return "metodo no existe";
			}
		}else{
			return "no f";
		}
		
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
		return View::make('views.view2');
	}
	
	public function getVista3()
	{
		return View::make('views.view3');
	}
	
	public function getVista4()
	{
		return View::make('views.view4');
	}
	
	public function getVista5()
	{
		return View::make('views.view5');
	}
	
	public function getVista6()
	{
		return View::make('views.view6');
	}
	
	public function getVista7()
	{
		return View::make('views.view7');
	}
	
	public function getVista8()
	{
		return View::make('views.view8');
	}
	
	public function getVista9()
	{
		return View::make('views.view9');
	}
	
	public function getVista10()
	{
		return View::make('views.view10');
	}



}

?>