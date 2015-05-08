<?php
class ViewsComision extends BaseController
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

public function getListacomisiones()
{
	return View::make('views.comision.listacomisiones');
}

public function getEditarcomision()
{
		return View::make('views.comision.editarcomision');
}

	
}
?>