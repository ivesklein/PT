<?php

//

class ViewsHojaRuta extends BaseController
{
	
	public function __construct()
	{
		$this->beforeFilter(function(){
			if(!Auth::check()){
				return View::make('views.expired');
			}
		});
	}



	// HOJA DE RUTA //
	public function getListahojasruta()
	{
		$ahead = array("Grupo","Estado","Ver");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		//if($temas)

		$temas = Staff::find(Auth::user()->id)->guias()->wherePeriodo(Periodo::active())->get();

		if(!$temas->isEmpty()){

			foreach ($temas as $tema) {

					$st1 = explode("@",$tema->student1);
			    	$st2 = explode("@",$tema->student2);
			    	$grupo = $st1[0]." & ".$st2[0]."(".$tema->id.")";

			    	if($tema->hojaruta == "falta-guia"){
			    		$evallink = url("#/firmarhojaprofesor/".$tema->id);
			    		$buttons = View::make("html.buttonlink",array("title"=>"Firmar","color"=>"green","url"=>$evallink));
			    	}else{
			    		$evallink = url("#/hojaprofesor/".$tema->id);
			    		$buttons = View::make("html.buttonlink",array("title"=>"Ver","color"=>"cyan","url"=>$evallink));
			    	}

			    	$nota = View::make("html.nota",array());
					$id = $tema->id;

					if(empty($tema->hojaruta)){
						$estado = "En blanco";
					}elseif(strpos($tema->hojaruta, "@")!==false){
						$estado = "Una firma";
					}elseif($tema->hojaruta=="falta-guia"){
						$estado = "Solicitud de Firma";
					}else{
						$estado = "otro";
					}



					$content = View::make("table.cell",array("content"=>$grupo));
					$content .= View::make("table.cell",array("content"=>$estado));
					$content .= View::make("table.cell",array("content"=>$buttons));
					$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
			}

		}else{
			$message = "No hay grupos activos.";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}

		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		return View::make('views.hojaruta.lista',array("table"=>$table));
	}

	public function getFirmarhojaprofesor()
	{

		$declaracion = Texto::texto("declaracion-profesor","Declaro ante mi que el trabajo es digno de llamar memoria de Ingeniería.");
		$dec = array("declaracion"=>$declaracion);

		return View::make('views.hojaruta.firmaprofesor', $dec);
	}

	public function getDefiniraleatorio()
	{
		
		$ahead = array("Grupo","Tema","Asignar");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		//$soap = new PMsoap;	
		//$soap->login();
		//$res = $soap->caseList();
		$subjs = Subject::wherePeriodo(Periodo::active())->whereHojaruta("asignar-revisor")->get();
		//$subjs = Subject::whereStatus("confirm")->get();

		if(!$subjs->isEmpty()){

			foreach ($subjs as $subj) {

				$st1 = explode("@",$subj->student1);
		    	$st2 = explode("@",$subj->student2);
		    	$grupo = $st1[0]." & ".$st2[0]."(".$subj->id.")";

				$tema = $subj->subject;
				$id = $subj->id;

				$evallink = url("#/hojaasignar/".$subj->id);
			    $buttons = View::make("html.buttonlink",array("title"=>"Ver","color"=>"cyan","url"=>$evallink));

				$content = View::make("table.cell",array("content"=>$grupo));
				$content .= View::make("table.cell",array("content"=>$tema));
				$content .= View::make("table.cell",array("content"=>$buttons));
				$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
				/*else{

					$content = View::make("table.cell",array("content"=>$case->delIndex));
					$content .= View::make("table.cell",array("content"=>$case->guid));
					$body .= View::make("table.row",array("content"=>$content));
				}*/
			}

		}else{
			$message = "No hay Hojas de Ruta a asignar.";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		//return View::make('views.guias.confirmarguias-ay', array("table"=>$table));

		return View::make('views.hojaruta.definiraleatorio', array("table"=>$table));
	}

	public function getHojaasignar()
	{
		
		return View::make('views.hojaruta.asignar');
	}


	public function getRevisartemas()
	{
		$ahead = array("Grupo","Tema","Revisar");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		//$soap = new PMsoap;	
		//$soap->login();
		//$res = $soap->caseList();
		
		//los mios???
		//$subjs = Subject::wherePeriodo(Periodo::active())->whereHojaruta("en-revision")->get();

		$subjs = Staff::find(Auth::user()->id)->revisor()->wherePeriodo(Periodo::active())->whereHojaruta("en-revision")->get();


		//$subjs = Subject::whereStatus("confirm")->get();

		if(!$subjs->isEmpty()){

			foreach ($subjs as $subj) {

				$st1 = explode("@",$subj->student1);
		    	$st2 = explode("@",$subj->student2);
		    	$grupo = $st1[0]." & ".$st2[0]."(".$subj->id.")";

				$tema = $subj->subject;
				$id = $subj->id;

				$evallink = url("#/revisartema/".$subj->id);
			    $buttons = View::make("html.buttonlink",array("title"=>"Revisar","color"=>"cyan","url"=>$evallink));

				$content = View::make("table.cell",array("content"=>$grupo));
				$content .= View::make("table.cell",array("content"=>$tema));
				$content .= View::make("table.cell",array("content"=>$buttons));
				$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
				/*else{

					$content = View::make("table.cell",array("content"=>$case->delIndex));
					$content .= View::make("table.cell",array("content"=>$case->guid));
					$body .= View::make("table.row",array("content"=>$content));
				}*/
			}

		}else{
			$message = "No hay temas asignados para revisar.";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		//return View::make('views.guias.confirmarguias-ay', array("table"=>$table));

		return View::make('views.hojaruta.listarevisar', array("table"=>$table));
	}

	public function getRevisartema()
	{
		$declaracion = Texto::texto("declaracion-revisor","Declaro ante mi que el trabajo tiene un formato acorde a los estandares de la UAI.");
		$dec = array("declaracion"=>$declaracion);

		return View::make('views.hojaruta.revisar', $dec);
	}

	public function getReasignartemas()
	{
		$ahead = array("Grupo","Tema","Revisor","Reasignar");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		//$soap = new PMsoap;	
		//$soap->login();
		//$res = $soap->caseList();
		
		//los mios???
		$subjs = Subject::wherePeriodo(Periodo::active())->whereHojaruta("en-revision")->get();


		//$subjs = Subject::whereStatus("confirm")->get();

		if(!$subjs->isEmpty()){

			foreach ($subjs as $subj) {

				$st1 = explode("@",$subj->student1);
		    	$st2 = explode("@",$subj->student2);
		    	$grupo = $st1[0]." & ".$st2[0]."(".$subj->id.")";

				$tema = $subj->subject;
				$id = $subj->id;

				$profs = $subj->revisor()->get();
				if(!$profs->isEmpty()){
					$prof = $profs->first();
					$profe = $prof->wc_id;
				}else{
					$profe = "";
				}


				$evallink = url("#/reasignartema/".$subj->id);
			    $buttons = View::make("html.buttonlink",array("title"=>"Reasignar","color"=>"cyan","url"=>$evallink));

				$content = View::make("table.cell",array("content"=>$grupo));
				$content .= View::make("table.cell",array("content"=>$tema));
				$content .= View::make("table.cell",array("content"=>$profe));
				$content .= View::make("table.cell",array("content"=>$buttons));
				$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
				/*else{

					$content = View::make("table.cell",array("content"=>$case->delIndex));
					$content .= View::make("table.cell",array("content"=>$case->guid));
					$body .= View::make("table.row",array("content"=>$content));
				}*/
			}

		}else{
			$message = "No hay temas asignados.";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		//return View::make('views.guias.confirmarguias-ay', array("table"=>$table));

		return View::make('views.hojaruta.listareasignar', array("table"=>$table));
	}

	public function getReasignartema()
	{
		
		return View::make('views.hojaruta.reasignar');
	}

	public function getAprobartemas()
	{
		$ahead = array("Grupo","Tema","Ver");
		$head = "";
		foreach ($ahead as $value) {
			$head .= View::make('table.head',array('title'=>$value));
		}

		$body="";

		//$soap = new PMsoap;	
		//$soap->login();
		//$res = $soap->caseList();
		
		//los mios???
		$subjs = Subject::wherePeriodo(Periodo::active())->whereHojaruta("revisada")->get();


		//$subjs = Subject::whereStatus("confirm")->get();

		if(!$subjs->isEmpty()){

			foreach ($subjs as $subj) {

				$st1 = explode("@",$subj->student1);
		    	$st2 = explode("@",$subj->student2);
		    	$grupo = $st1[0]." & ".$st2[0]."(".$subj->id.")";

				$tema = $subj->subject;
				$id = $subj->id;

				$profs = $subj->revisor()->get();
				if(!$profs->isEmpty()){
					$prof = $profs->first();
					$profe = $prof->wc_id;
				}else{
					$profe = "";
				}


				$evallink = url("#/aprobartema/".$subj->id);
			    $buttons = View::make("html.buttonlink",array("title"=>"Ver","color"=>"cyan","url"=>$evallink));

				$content = View::make("table.cell",array("content"=>$grupo));
				$content .= View::make("table.cell",array("content"=>$tema));
				$content .= View::make("table.cell",array("content"=>$buttons));
				$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
				/*else{

					$content = View::make("table.cell",array("content"=>$case->delIndex));
					$content .= View::make("table.cell",array("content"=>$case->guid));
					$body .= View::make("table.row",array("content"=>$content));
				}*/
			}

		}else{
			$message = "No hay temas listos para aprobar.";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		//return View::make('views.guias.confirmarguias-ay', array("table"=>$table));

		return View::make('views.hojaruta.listaaprobar', array("table"=>$table));
	}

	public function getAprobartema()
	{
		$declaracion = Texto::texto("declaracion-secretaria","Declaro ante mi que el trabajo cumple con todos los requisitos para presentarse a defensa.");
		$dec = array("declaracion"=>$declaracion);
		return View::make('views.hojaruta.aprobar', $dec);
	}

	// HOJA DE RUTA //




}

?>