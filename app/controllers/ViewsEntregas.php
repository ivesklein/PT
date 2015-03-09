<?php

//

class ViewsEntregas extends BaseController
{
	
	public function __construct()
	{
		$this->beforeFilter(function(){
			if(!Auth::check()){
				return View::make('views.expired');
			}
		});
	}

	public function getTareas()
	{	

		$res = Periodo::active_obj();
		if($res!="false"){
			if(empty($res->resources)){
				$dis = false;
			}else{
				if($res->resources==1){
					$dis = true;
				}else{
					$dis = false;
				}
			}
		}else{
			$dis = true;
		}

		$data = array();
		$tareas = Tarea::wherePeriodo_name($res->name)->orderBy('n', 'ASC')->where("tipo","<",3)->get();

		if(!$tareas->isEmpty()){
			foreach ($tareas as $tarea) {
				$cdate = Carbon::parse($tarea->date);
				$data[$tarea->n] = array("title"=>$tarea->title, "date"=>$cdate->format('m/d/Y'), "tipo"=>$tarea->tipo);
				if(!empty($tarea->wc_uid)){
					$data[$tarea->n]['wc'] = $tarea->wc_uid;
				}
			}
		}


		return View::make('views.periodos.tareas', array("dis"=>$dis,"data"=>$data));
	}

	public function getListanotas()
	{
		$ahead = array("Grupo","Tema","Evaluar");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		//ver si hay entrega finalizada
		//
		//$entregas = Tarea::where('date', '<', Carbon::now())->where('date', '>', Carbon::now()->subDays(14))->get();

		//if(!$entregas->isEmpty()){

			//foreach ($entregas as $entrega) {

				//if(!empty($entrega->wc_uid)){
				//	$wclink = "http://webcursos.uai.cl/mod/assign/view.php?id=".$entrega->wc_uid."&action=grading";
				//}else{
				//	$wclink = "#";
				//

				//$tituloentrega = $entrega->title;

				//if(true){
					$temas = Staff::find(Auth::user()->id)->guias()->wherePeriodo(Periodo::active())->get();
				//}else{
				//	$temas = Staff::find(Auth::user()->id)->comision()->wherePeriodo(Periodo::active())->get();
				//}

				if(!$temas->isEmpty()){

					
					//$link = View::make("html.buttonlink",array("title"=>"Ver Entrega","color"=>"blue","url"=>$wclink,"tab"=>1));
					


					foreach ($temas as $tema) {

							$st1 = explode("@",$tema->student1);
					    	$st2 = explode("@",$tema->student2);
					    	$grupo = $st1[0]." & ".$st2[0]."(".$tema->id.")";

					    	$evallink = url("#/evaluartarea/".$tema->id);


					    	$buttons = View::make("html.buttonlink",array("title"=>"Ingresar","color"=>"cyan","url"=>$evallink));
					    	//buscar en tabla notas
					    	//$notaarray = array();
					    	//if(!empty())
					    	$nota = View::make("html.nota",array());
							$id = $tema->id;


							$content = View::make("table.cell",array("content"=>$grupo));
							$content .= View::make("table.cell",array("content"=>$tema->subject));
							$content .= View::make("table.cell",array("content"=>$buttons));
							$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
					}

				}else{
					$message = "No hay grupos a evaluar";
					$content = View::make("table.cell",array("content"=>$message));
					$body .= View::make("table.row",array("content"=>$content));

				}

		/*	}
		}else{
			$message = "No hay entregas a evaluar";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}*/
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		return View::make('views.temas.listanotas', array("table"=>$table));
	}

	public function getEvaluartarea()
	{
		
		return View::make('views.temas.evaluartarea');
	}

	public function getRevisarnotas()
	{
		$ahead = array("Grupo","Tema","Alerta","Ver");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

				$temas = Subject::active()->get();

				if(!$temas->isEmpty()){

					foreach ($temas as $tema) {

							$st1 = explode("@",$tema->student1);
					    	$st2 = explode("@",$tema->student2);
					    	$grupo = $st1[0]." & ".$st2[0]."(".$tema->id.")";

					    	$evallink = url("#/revisarnota/".$tema->id);


					    	$buttons = View::make("html.buttonlink",array("title"=>"Ingresar","color"=>"cyan","url"=>$evallink));

							$id = $tema->id;

							$content = View::make("table.cell",array("content"=>$grupo));
							$content .= View::make("table.cell",array("content"=>$tema->subject));
							$content .= View::make("table.cell",array("content"=>""));
							$content .= View::make("table.cell",array("content"=>$buttons));
							$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
					}

				}else{
					$content = View::make("table.cell",array("content"=>$message));
					$body .= View::make("table.row",array("content"=>$content));

				}

		/*	}
		}else{
			$message = "No hay entregas a evaluar";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}*/
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		return View::make('views.temas.listanotas', array("table"=>$table));
	}

	public function getRevisarnota()
	{
		return View::make('views.temas.revisarnota');
	}


}

?>