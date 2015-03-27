<?php
class ViewsTexto extends BaseController
{

	public function __construct()
	{
		$this->beforeFilter(function(){
			if(!Auth::check()){
				return View::make('views.expired');
			}
		});
	}

	public function getModificar()
	{
		
		return View::make('views.texto');
	}

	
}
?>