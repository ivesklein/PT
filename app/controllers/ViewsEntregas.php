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
			$dis = false;
		}else{
			$dis = true;
		}

		$data = array();
		$tareas = Tarea::wherePeriodo_name($res->name)->orderBy('n', 'ASC')->where("tipo","<",3)->get();

		if(!$tareas->isEmpty()){
			foreach ($tareas as $tarea) {
				$cdate = Carbon::parse($tarea->date);
				$data[$tarea->n] = array("title"=>$tarea->title, "date"=>$cdate->format('m/d/Y'), "tipo"=>$tarea->tipo, "entrega"=>$tarea->uptime, "eval"=>$tarea->evaltime);
				if(!empty($tarea->wc_uid)){
					$data[$tarea->n]['wc'] = $tarea->wc_uid;
				}
			}
		}


		return View::make('views.periodos.tareas', array("dis"=>$dis,"data"=>$data));
	}

	public function getListanotas()
	{
		$ahead = array("Grupo","Tema","Evaluar", "Pendiente");
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
					$entregas = Tarea::wherePeriodo_name(Periodo::active())->where("tipo","<",3)->where('date', '<', Carbon::now())->get();

					//->where('date', '>', Carbon::now()->subDays(14))

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

					    	$pendiente = "";
					    	if(!$entregas->isEmpty()){
					    		$pendiente = 0;
				                foreach ($entregas as $entrega) {
				                	if(Carbon::parse($entrega->date)>Carbon::now()->subDays($entrega->evaltime)){
					                    $nota = Nota::whereTarea_id($entrega->id)->whereSubject_id($tema->id)->first();
					                    if(empty($nota)){
					                        $pendiente++;
					                    }else{
					                        if(empty($nota->nota)){
					                            $pendiente++;
					                        }
					                    }
				                	}
				                }
				                if($pendiente>0){
				                	$pendiente = '<span class="badge badge-danger main-badge">'.$pendiente.'</span>';
					    		}
					    	}

					    	$nota = View::make("html.nota",array());
							$id = $tema->id;


							$content = View::make("table.cell",array("content"=>$grupo));
							$content .= View::make("table.cell",array("content"=>$tema->subject));
							$content .= View::make("table.cell",array("content"=>$buttons));
							$content .= View::make("table.cell",array("content"=>$pendiente));
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
					$message = "No hay entregas a evaluar";
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

	public function getNotas()
	{
		$ahead = array("Grupo","Tema","Profesor Guía","Alumnos");
		//agregar tareas
		$tareas = Tarea::wherePeriodo_name(Periodo::active())->where("tipo","<",5)->get();
		foreach ($tareas as $tarea) {
			$ahead[] = $tarea->title;
		}
		$ahead[] = "Modificar";


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

					    	$a1content = View::make("table.cell",array("content"=>$grupo, "span"=>2));
					    	$tool = View::make("html.tooltip",array("title"=>$tema->subject));
							$a1content .= View::make("table.cell",array("content"=>$tool, "span"=>2));
							$a1content .= View::make("table.cell",array("content"=>$tema->adviser, "span"=>2));

					    	$a1 = Student::whereWc_id($tema->student1)->first();
					    	$a1content .= View::make("table.cell",array("content"=>$a1->name." ".$a1->surname));
					    	$a2 = Student::whereWc_id($tema->student2)->first();
					    	$a2content = View::make("table.cell",array("content"=>$a2->name." ".$a2->surname));
					    	
					    	foreach ($tareas as $tarea) {
								$id = $tarea->id;
								$nota = Nota::whereSubject_id($tema->id)->whereTarea_id($tarea->id)->first();
								
								if($tarea->tipo<3){//tiene fecha en tarea
									$date = CarbonLocale::parse($tarea->date);
									$now = Carbon::now();
									if($date>$now){//futura
										$a1content .= View::make("table.cell",array("content"=>""));
										$a2content .= View::make("table.cell",array("content"=>""));
									}elseif($date<$now->subDays($tarea->evaltime)){//ya pasó el tiempo de eval
										
										if(!empty($nota)){
											$notas = json_decode($nota->nota);
											$ca1 = empty($notas[0])?'<div style="color: red; font-size: 20px;" class="glyphicon glyphicon-warning-sign"></div>':$notas[0];
											$ca2 = empty($notas[1])?'<div style="color: red; font-size: 20px;" class="glyphicon glyphicon-warning-sign"></div>':$notas[1];
											$a1content .= View::make("table.cell",array("content"=>$ca1));
											$a2content .= View::make("table.cell",array("content"=>$ca2));
											
										}else{
											$a1content .= View::make("table.cell",array("content"=>'<div style="color: red; font-size: 20px;" class="glyphicon glyphicon-warning-sign"></div>'));
											$a2content .= View::make("table.cell",array("content"=>'<div style="color: red; font-size: 20px;" class="glyphicon glyphicon-warning-sign"></div>'));
										}

									}else{//dentro de periododo de evaluacion

										if(!empty($nota)){
											$notas = json_decode($nota->nota);
											$ca1 = empty($notas[0])?'<div class="glyphicon glyphicon-edit" style="font-size: 20px; color: rgb(0, 30, 255);"></div>':$notas[0];
											$ca2 = empty($notas[1])?'<div class="glyphicon glyphicon-edit" style="font-size: 20px; color: rgb(0, 30, 255);"></div>':$notas[1];
											$a1content .= View::make("table.cell",array("content"=>$ca1));
											$a2content .= View::make("table.cell",array("content"=>$ca2));
										}else{
											$a1content .= View::make("table.cell",array("content"=>'<div class="glyphicon glyphicon-edit" style="font-size: 20px; color: rgb(0, 30, 255);"></div>'));
											$a2content .= View::make("table.cell",array("content"=>'<div class="glyphicon glyphicon-edit" style="font-size: 20px; color: rgb(0, 30, 255);"></div>'));
										}
									
									}
								}else{

									if(!empty($nota)){
										$notas = json_decode($nota->nota);
										$ca1 = empty($notas[0])?'<div class="glyphicon glyphicon-edit" style="font-size: 20px; color: rgb(0, 30, 255);"></div>':$notas[0];
										$ca2 = empty($notas[1])?'<div class="glyphicon glyphicon-edit" style="font-size: 20px; color: rgb(0, 30, 255);"></div>':$notas[1];
										$a1content .= View::make("table.cell",array("content"=>$ca1));
										$a2content .= View::make("table.cell",array("content"=>$ca2));
									}else{
										//buscar evento
										if($tarea->tipo==3){//pre
											$evento = CEvent::whereDetail($tema->id)->whereType('Predefensa')->first();
											if(empty($evento)){
												$a1content .= View::make("table.cell",array("content"=>"Sin fecha"));
												$a2content .= View::make("table.cell",array("content"=>"Sin fecha"));
											}else{
												$fecha = CarbonLocale::parse($evento->start);
												$a1content .= View::make("table.cell",array("content"=>$fecha->diffParaHumanos()));
												$a2content .= View::make("table.cell",array("content"=>$fecha->diffParaHumanos()));
											}

										}elseif($tarea->tipo==4){//def
											$evento = CEvent::whereDetail($tema->id)->whereType('Defensa')->first();
											if(empty($evento)){
												$a1content .= View::make("table.cell",array("content"=>"Sin fecha"));
												$a2content .= View::make("table.cell",array("content"=>"Sin fecha"));
											}else{
												$fecha = CarbonLocale::parse($evento->start);
												$a1content .= View::make("table.cell",array("content"=>$fecha->diffParaHumanos()));
												$a2content .= View::make("table.cell",array("content"=>$fecha->diffParaHumanos()));
											}
										}
									}

								}
							}

							$id = $tema->id;

							$a1content .= View::make("table.cell",array("content"=>$buttons, "span"=>2));

							$body .= View::make("table.row",array("content"=>$a1content, "id"=>$id));
							$body .= View::make("table.row",array("content"=>$a2content, "id"=>$id));
					}

				}else{
					$message = "No hay grupos activos";
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
		$script = '
		</script>
		<script src="js/tooltip.js"></script>
		<script src="js/popover.js"></script>
		<script>
		$(function () {
					  $(\'[data-toggle="popover"]\').popover()
					})';
		return View::make('table.tableview', array("table"=>$table, "script"=>$script));		
	}


}

?>