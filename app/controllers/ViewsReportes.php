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
		return View::make('views.reportes.rezagado');
	}

	public function getListamemorias()
	{
		return View::make('views.reportes.memorias');
	}

	public function getListamemoriasac()
	{
		return View::make('views.reportes.memorias-ac');
	}

	public function getListamemoriasa()
	{
		return View::make('views.reportes.memorias-a');
	}

	public function getListamemoriasah()
	{
		return View::make('views.reportes.memorias-a-h');
	}

	public function getEvaluaciones()
	{
		return View::make('views.reportes.evaluaciones');
	}

	public function getTarea()
	{
		return View::make('views.reportes.tarea');
	}

	public function getHojaruta()
	{
		return View::make('views.reportes.hojaruta');
	}

	public function getEvalguias()
	{
		return View::make('views.reportes.evalguias');
	}

	public function getGmt()
	{
		return View::make('views.reportes.gmt');
	}

	public function getGma()
	{
		return View::make('views.reportes.gma');
	}

}
?>