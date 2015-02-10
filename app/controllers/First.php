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

		$per = Periodo::whereStatus("active")->get();
		if(!$per->isEmpty()){
			$item = $per->first();
			$drop = View::make('html.dropitem',array("value"=>$item->name,"title"=>$item->name));
		}else{
			$drop = View::make('html.dropitem',array("value"=>"0","title"=>"No hay periodo Activo"));
		}

		return View::make('views.periodos.view1',array("drop"=>$drop));
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

	public function getPeriodos()
	{
		$ahead = array("Periodo","Creado en","Estado", "Controles");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		$pers = Periodo::all();
		if(!$pers->isEmpty()){

			$buttonactivate = View::make("table.button",array("title"=>"Activar","color"=>"green","class"=>"activate"));
			$buttonterminate = View::make("table.button",array("title"=>"Terminar","color"=>"red","class"=>"closeper"));			

			$status1 = View::make("html.label",array("title"=>"Draft","color"=>"cyan"));
			$status2 = View::make("html.label",array("title"=>"Activo","color"=>"green"));
			$status3 = View::make("html.label",array("title"=>"Cerrado","color"=>"blue"));

			foreach ($pers as $per) {

				//$res2 = $soap->taskCase($case->guid);

				//$subj = Subject::wherePm_uid($case->guid)->first();

				$name = $per->name;
				$fecha = $per->created_at;
				$button = "";

				$array = array("content"=>"","id"=>$per->id);

				switch ($per->status) {
					case 'draft':
						$button = $buttonactivate;
						$status = $status1;
						break;
					case 'active':
						$array['class'] = "success";
						$button = $buttonterminate;
						$status = $status2;
						break;
					case 'closed':
						$array['class'] = "active";
						$status = $status3;
						break;
					
					default:
						$status = "null";
						break;
				}


				$array["content"] = View::make("table.cell",array("content"=>$name));
				$array["content"] .= View::make("table.cell",array("content"=>$fecha));
				$array["content"] .= View::make("table.cell",array("content"=>$status));
				$array["content"] .= View::make("table.cell",array("content"=>$button));
				$body .= View::make("table.row",$array);
			}

		}else{
			$message = "No hay periodos";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		return View::make('views.periodos.periodoslist', array("table"=>$table));
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

				//$res2 = $soap->taskCase($case->guid);


				$subj = Subject::wherePeriodo(Periodo::active())->wherePm_uid($case->guid)->first();

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

				//$res2 = $soap->taskCase($case->guid);

				$subjs = Subject::wherePeriodo(Periodo::active())->wherePm_uid($case->guid)->get();
				
				if(!$subjs->isEmpty()){

					$subj = $subjs->first();
				
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
						$content .= View::make("table.cell",array("content"=>$subj->status));
						$body .= View::make("table.row",array("content"=>$content));
					}
				}else{
					$message = "Tema no registrado";
					$content = View::make("table.cell",array("content"=>$message));
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
		$subjs = Subject::wherePeriodo(Periodo::active())->whereStatus("confirm")->get();
		//$subjs = Subject::whereStatus("confirm")->get();

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

		$ahead = array("Nombre","Apellido","Mail","Boton");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		$staffs = Staff::all();

		if(!$staffs->isEmpty()){
			
			foreach ($staffs as $staff) {

					$name = $staff->name;
					$surname = $staff->surname;
					$mail = $staff->wc_id;
					$id = $staff->id;

					$array = array("items"=>array(
						"CA"=>array("title"=>"Cordinador Académico", "value"=>"CA"),
						"SA"=>array("title"=>"Secretario Académico", "value"=>"SA"),
						"P"=>array("title"=>"Profesor", "value"=>"P"),
						"PT"=>array("title"=>"Profesor Taller", "value"=>"PT"),
						"AY"=>array("title"=>"Auyudante Taller", "value"=>"AY")
					));

					$role = Permission::whereStaff_id($id)->first();

					$array['items'][$role->permission]["sel"]=1;

					$buttons = View::make("html.drop", $array);

					$content = View::make("table.cell",array("content"=>$name));
					$content .= View::make("table.cell",array("content"=>$surname));
					$content .= View::make("table.cell",array("content"=>$mail));
					$content .= View::make("table.cell",array("content"=>$buttons));
					$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
				
			}

		}else{
			$message = "No Usuarios";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		return View::make('views.users.profesores', array("table"=>$table));



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

	//  CALENDARIO  //
	public function getCalendario()
	{
		return View::make('views.calendario.calendario');
	}

	public function getCoordefensa()
	{
		return View::make('views.calendario.coordefensa');
	}
	//  CALENDARIO  //

	// COMISIONES  //

	public function getConfirmarcomision()
	{
		$ahead = array("Tema","Alumno 1","Alumno 2","Confirmar");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		$temas = Staff::find(Auth::user()->id)->comision()->wherePeriodo(Periodo::active())->get();

		if(!$temas->isEmpty()){

			$buttons = View::make("table.yesno");
			
			foreach ($temas as $tema) {

				if($tema->pivot->status=="confirmar"){
					$title = $tema->subject;
					$alumno1 = $tema->student1;
					$alumno2 = $tema->student2;
					$id = $tema->id;


					$content = View::make("table.cell",array("content"=>$title));
					$content .= View::make("table.cell",array("content"=>$alumno1));
					$content .= View::make("table.cell",array("content"=>$alumno2));
					$content .= View::make("table.cell",array("content"=>$buttons));
					$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
				}
			}

		}else{
			$message = "No hay comisiones pendientes de confirmación";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		return View::make('views.comision.confirmar', array("table"=>$table));
	}

	public function getWebcursos()
	{
		
		return View::make('views.webcursos.webcursos');
	}

}

?>