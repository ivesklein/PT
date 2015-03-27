<?php

//

class ViewsFirst extends BaseController
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




	//  PERIODOS  //
	public function getItemas()
	{

		$per = Periodo::whereStatus("active")->get();
		if(!$per->isEmpty()){
			$item = $per->first();
			$periodo = $item->name."<input type='hidden' value='".$item->name."' name='periodo'>";
		}else{
			$periodo = "No hay periodo Activo<input type='hidden' value='0' name='periodo'>";
		}

		return View::make('views.periodos.agregartemas',array("periodo"=>$periodo));
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
		$ahead = array("Semestre","Creado en","Estado", "Controles");
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

			$active = Periodo::active();

			foreach ($pers as $per) {

				//$res2 = $soap->taskCase($case->guid);

				//$subj = Subject::wherePm_uid($case->guid)->first();

				$name = $per->name;
				$fecha = $per->created_at;
				$button = "";

				$array = array("content"=>"","id"=>$per->id);

				switch ($per->status) {
					case 'draft':
						if($active=="false"){
							$button = $buttonactivate;
						}
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
	

	public function getListatemas()
	{	
		$ahead = array("Tema","Alumno 1","Alumno 2");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		$subjs = Subject::active()->get();

		if(!$subjs->isEmpty()){
			
			foreach ($subjs as $subj) {

				$tema = $subj->subject;
				$alumno1 = $subj->student1;
				$alumno2 = $subj->student2;
				$id = $subj->id;

				$content = View::make("table.cell",array("content"=>$tema));
				$content .= View::make("table.cell",array("content"=>$alumno1));
				$content .= View::make("table.cell",array("content"=>$alumno2));
				$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
			}

		}else{
			$message = "No hay Memorias";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}

		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		if(Rol::actual()=="AY" || Rol::actual()=="PT"){
		$script = View::make('scripts.cambiartema', array() );
		}else{
			$script ="";
		}
		return View::make('table.tableview', array("title"=>"Memorias Activas","table"=>$table, "script"=>$script));
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



		$subjs = Subject::wherePeriodo(Periodo::active())->whereAdviser(Auth::user()->wc_id)->whereStatus("confirm")->get();

		if(!$subjs->isEmpty()){
			$buttons = View::make("table.yesno");
			
			foreach ($subjs as $subj) {

				$tema = $subj->subject;
				$alumno1 = $subj->student1;
				$alumno2 = $subj->student2;
				$id = $subj->id;

				$content = View::make("table.cell",array("content"=>$tema));
				$content .= View::make("table.cell",array("content"=>$alumno1));
				$content .= View::make("table.cell",array("content"=>$alumno2));
				$content .= View::make("table.cell",array("content"=>$buttons));
				$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
			
				
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

		if(false){//con pm

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


		}else{//sin pm

			$subjs = Subject::wherePeriodo(Periodo::active())->whereStatus("not-confirmed")->get();

			if(!$subjs->isEmpty()){

				$drop = View::make("table.profesor-drop");
				$save = View::make("table.btn-agregar");

				foreach ($subjs as $subj) {
					
					$tema = $subj->subject;
					$alumno1 = $subj->student1;
					$alumno2 = $subj->student2;
					$id = $subj->id;

					$content = View::make("table.cell",array("content"=>$tema));
					$content .= View::make("table.cell",array("content"=>$alumno1));
					$content .= View::make("table.cell",array("content"=>$alumno2));
					$content .= View::make("table.cell",array("content"=>$drop));
					$content .= View::make("table.cell",array("content"=>$save));
					$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
				}

			}else{
				$message = "No hay temas rechazados";
				$content = View::make("table.cell",array("content"=>$message));
				$body .= View::make("table.row",array("content"=>$content));

			}

		}

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

		$subjs = Subject::wherePeriodo(Periodo::active())->whereStatus("confirm")->get();
		//$subjs = Subject::whereStatus("confirm")->get();

		if(!$subjs->isEmpty()){

			$buttons = View::make("table.yesno");

			foreach ($subjs as $subj) {

				$tema = $subj->subject;
				$alumno1 = $subj->student1;
				$alumno2 = $subj->student2;
				$profesor = $subj->adviser;
				$id = $subj->id;

				$content = View::make("table.cell",array("content"=>$tema));
				$content .= View::make("table.cell",array("content"=>$alumno1));
				$content .= View::make("table.cell",array("content"=>$alumno2));
				$content .= View::make("table.cell",array("content"=>$profesor));
				$content .= View::make("table.cell",array("content"=>$buttons));
				$body .= View::make("table.row",array("content"=>$content, "id"=>$id));

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