<?php
class ViewsReportes extends BaseController
{

	public function __construct()
	{
		$this->beforeFilter(function(){
			if(!Auth::check()){
				return View::make('views.expired');
			}
		});
	}


	public function getListarezagados()
	{
		return View::make('views.reportes.rezagados');
	}

	public function getRezagado()
	{
		return 2;
	}

}
?>