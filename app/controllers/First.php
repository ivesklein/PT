<?php

//

class First extends BaseController
{
	
	public function __construct()
	{
		$this->beforeFilter(function(){
			if(!Auth::check()){
				return View::make('views.expired');
			}
		});
	}

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

	public function getItemas()
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
	
	public function getConfirmarguia()
	{
		$ahead = array("Tema","Alumno 1","Alumno 2","Confirmar");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}




		$body="";
		$soap = new PMsoap;
	
		$soap->login();
		
		$res = $soap->caseList();

		if(isset($res['ok'])){
			foreach ($res['ok'] as $case) {
				if($case->delIndex==2){
					$subj = Subject::wherePm_uid($case->guid)->first();
					$tema = $subj->subject;
					$alumno1 = $subj->student1;
					$alumno2 = $subj->student2;
					$id = $case->guid;
					$buttons = View::make("table.yesno");

					$content = View::make("table.cell",array("content"=>$tema));
					$content .= View::make("table.cell",array("content"=>$alumno1));
					$content .= View::make("table.cell",array("content"=>$alumno2));
					$content .= View::make("table.cell",array("content"=>$buttons));
					$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
				}
			}

		}else{
			$message = "No hay temas pendientes de confirmación";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);

		$table = View::make('table.table', array("head"=>$head,"body"=>$body));

		return View::make('views.view6', array("table"=>$table));
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