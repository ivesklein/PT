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


	//  ESTRUCTURA  //
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
	//  ESTRUCTURA  //


	//  LOGIN  //
	public function getLogin()
	{
		return View::make('login.login');
	}
	//  LOGIN  //

	//  PERIODOS  //
	public function getItemas()
	{
		return View::make('views.periodos.view1');
	}

	public function getVista2()
	{
		//digerir csv
		return View::make('views.periodos.view2');
	}
	
	public function getVista3()
	{
		return View::make('views.periodos.view3');
	}
	
	public function getVista4()
	{
		return View::make('views.periodos.view4');
	}
	//  PERIODOS  //
	

	public function getVista5()
	{
		return View::make('views.view5');
	}
	
	//  GUIAS  //
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
			$buttons = View::make("table.yesno");
			
			foreach ($res['ok'] as $case) {

				$res2 = $soap->taskCase($case->guid);


				$subj = Subject::wherePm_uid($case->guid)->first();

				if($subj->status=="confirm"){
					$tema = $subj->subject;
					$alumno1 = $subj->student1;
					$alumno2 = $subj->student2;
					$id = $case->guid;


					$content = View::make("table.cell",array("content"=>$tema));
					$content .= View::make("table.cell",array("content"=>$alumno1));
					$content .= View::make("table.cell",array("content"=>$alumno2));
					$content .= View::make("table.cell",array("content"=>$buttons));
					$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
				}else{

					$content = View::make("table.cell",array("content"=>$case->delIndex));
					$content .= View::make("table.cell",array("content"=>$case->guid));
					$body .= View::make("table.row",array("content"=>$content));
				}
			}

		}else{
			$message = "No hay temas pendientes de confirmación";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		return View::make('views.guias.view6', array("table"=>$table));
	}

	public function getAsignarguia()
	{
		$ahead = array("Tema","Alumno 1","Alumno 2","Profesor","Asignar");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";
		$soap = new PMsoap;	
		$soap->login();
		$res = $soap->caseList();

		if(isset($res['ok'])){

			$drop = View::make("table.profesor-drop");
			$save = View::make("table.btn-agregar");

			foreach ($res['ok'] as $case) {

				$res2 = $soap->taskCase($case->guid);

				$subj = Subject::wherePm_uid($case->guid)->first();

				if($subj->status=="not-confirmed"){
					$tema = $subj->subject;
					$alumno1 = $subj->student1;
					$alumno2 = $subj->student2;
					$id = $case->guid;


					$content = View::make("table.cell",array("content"=>$tema));
					$content .= View::make("table.cell",array("content"=>$alumno1));
					$content .= View::make("table.cell",array("content"=>$alumno2));
					$content .= View::make("table.cell",array("content"=>$drop));
					$content .= View::make("table.cell",array("content"=>$save));
					$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
				}else{

					$content = View::make("table.cell",array("content"=>$case->delIndex));
					$content .= View::make("table.cell",array("content"=>$case->guid));
					$body .= View::make("table.row",array("content"=>$content));
				}
			}

		}else{
			$message = "No hay temas rechazados";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		return View::make('views.guias.asignarguia', array("table"=>$table));
	}

	public function getVista7()//lista guias por confirmar
	{
		$ahead = array("Tema","Alumno 1","Alumno 2","Profesor","Confirmar");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		//$soap = new PMsoap;	
		//$soap->login();
		//$res = $soap->caseList();
		$subjs = Subject::whereStatus("confirm")->get();
		if(!$subjs->isEmpty()){

			$buttons = View::make("table.yesno");

			foreach ($subjs as $subj) {

				//$res2 = $soap->taskCase($case->guid);

				//$subj = Subject::wherePm_uid($case->guid)->first();

				$tema = $subj->subject;
				$alumno1 = $subj->student1;
				$alumno2 = $subj->student2;
				$profesor = $subj->adviser;
				$id = $subj->pm_uid;


				$content = View::make("table.cell",array("content"=>$tema));
				$content .= View::make("table.cell",array("content"=>$alumno1));
				$content .= View::make("table.cell",array("content"=>$alumno2));
				$content .= View::make("table.cell",array("content"=>$profesor));
				$content .= View::make("table.cell",array("content"=>$buttons));
				$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
				/*else{

					$content = View::make("table.cell",array("content"=>$case->delIndex));
					$content .= View::make("table.cell",array("content"=>$case->guid));
					$body .= View::make("table.row",array("content"=>$content));
				}*/
			}

		}else{
			$message = "No hay temas por confirmar";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		return View::make('views.guias.confirmarguias-ay', array("table"=>$table));
	}
	//  GUIAS  //


	
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

	//  USUARIOS  //
	public function getProfesores()
	{
		return View::make('views.users.profesores');
	}

	public function getAlumnos()
	{
		return View::make('views.users.alumnos');
	}

	public function getAyudantes()
	{
		return View::make('views.users.ayudantes');
	}
	//  USUARIOS  //

}

?>