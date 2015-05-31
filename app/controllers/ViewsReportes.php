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
	
	public static function test()
    {
        return true;
    }

	public function getListarezagados()
	{
		return View::make('views.reportes.rezagados');
	}

	public function getRezagado()
	{
		return 2;
	}

	public function getListamemorias()
	{
		return View::make('views.reportes.memorias');
	}

	public function getListamemoriasa()
	{
		return View::make('views.reportes.memorias-a');
	}

	public function getListamemoriasah()
	{
		return View::make('views.reportes.memorias-a-h');
	}

	public function getAtrazoentrega()
	{
		return View::make('views.reportes.atrazo-entrega');
	}

	public function getAtrazohoja()
	{
		return View::make('views.reportes.atrazo-hoja');
	}

}
?>