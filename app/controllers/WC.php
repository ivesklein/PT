<?php

//

class WC extends BaseController
{
	
	public function getIndex()
	{
		return View::make("hello");
		//return Carbon::now();
		//return "hola";
	}

	public function postIndex()
	{

		if(isset($_POST['f'])){
			if(method_exists("PostWC", $_POST['f'])){
				return PostWC::$_POST['f']();
			}else{
				return "metodo no existe";
			}
		}else{
			return "no post, maybe size error";
		}
		//return View::make("hello");
		//return Carbon::now();
		//return "hola";
	}


	public function getAdd(){
		$cons = new Consumer;
		$cons->key = "webcursos";
		$cons->secret = "webcursos-secret";
		$cons->name = "Webcursos";
		$cons->save();

	}

	public function postWeb()
	{	
		//return View::make("hello");
		$lti = LTI::check();
		if($lti['status'] == "ok"){

			$mes = "No estás registrado en Queso.";

			$pt = Staff::whereWc_id($lti['email'])->get();
			if(!$pt->isEmpty()){
				$user = $pt->first()->rol;


				$mes = "Tu eres ".$user->permission." en Queso.";
			}

			return "Hola ".$lti['name']." ".$lti['surname']."
			<br>".$mes;
		}else{
			return print_r($lti);
		}

	}

	public function postNotas()
	{	
		//return View::make("hello");
		$lti = LTI::check();
		if($lti['status'] == "ok"){

			$mes = "No estás registrado en Queso.";

			$pt = Staff::whereWc_id($lti['email'])->get();
			if(!$pt->isEmpty()){
				$user = $pt->first()->rol;


				$mes = "Tu eres ".$user->permission." en Queso.";
			}

			$name = $lti['name']." ".$lti['surname'];

			Session::put('wc.user', $lti['email']);
			$user = $lti['email'];

			$notas = "";

			$temas = Subject::wherePeriodo(Periodo::active())->whereStudent1($user)->orWhere("student2",$user)->get();
			if(!$temas->isEmpty()){
				$tema = $temas->first();
				
				$tareas = Tarea::wherePeriodo_name(Periodo::active())->orderBy('n', 'ASC')->get();

				if(!$tareas->isEmpty()){

					foreach ($tareas as $tarea) {
						$title = $tarea->title;
						$date = CarbonLocale::parse($tarea->date);

						$active = 0; //0 es futura, 1 es activa, 2 es pasado con eval.
						$now = Carbon::now();
						if($date>$now){//futura
							$active = 0;
							$nota="";
							$feedback="";
						}else{
							$active = 1;
							$nota="";
							$feedback="";
							//get notas de tarea para el grupo
							$nota = Nota::whereSubject_id($tema->id)->whereTarea_id($tarea->id)->get();
							if(!$nota->isEmpty()){
								$notita = $nota->first();
								$nota = $notita->nota;
								$feedback = $notita->feedback;
							}
							
						}

						$notas .= View::make('lti.nota',array("active"=>$active,"title"=>$title,"tarea"=>$tarea->id,"nota"=>$nota));

					}

				}

			}

			return View::make("lti.notas",array("notas"=>$notas));

		}else{
			return print_r($lti);
		}

	}

	public function getNotas()
	{
		if(Session::get('wc.user' ,"0")!="0"){
			return View::make("lti.notas");
		}else{
			return "ah?";
		}
	}

	public function postEvaluacion()
	{	
		//return View::make("hello");
		$lti = LTI::check();
		if($lti['status'] == "ok"){

			$mes = "No estás registrado en Queso.";

			$pt = Staff::whereWc_id($lti['email'])->get();
			if(!$pt->isEmpty()){
				$user = $pt->first()->rol;


				$mes = "Tu eres ".$user->permission." en Queso.";
			}

			$name = $lti['name']." ".$lti['surname'];

			return View::make("lti.evaluacion");

		}else{
			return print_r($lti);
		}

	}

	public function showNota($n)
	{
		return View::make("lti.nota");
	}

	public function postYo()
	{

		/*
		$type = Input::get('lti_message_type');
		//$vtype = $type=="basic-lti-launch-request"?"true":"false";

		$version = Input::get('lti_version');//=="LTI-1p0"?"true":"false";

		//id instancia creada, un curso puede tener varias
		$resource_id = Input::get('resource_link_id');

		//id contexto (curso)
		$context_id = Input::get('context_id');

		//no readable user id
		$user_id = Input::get('user_id');

		//rol dentro del curso
		$roles = Input::get('roles');

		//clave que le damos previamente al cliente
		$oauth_consumer_key = Input::get('oauth_consumer_key');

		//random unico dentro de 90 minutos?
		$oauth_nonce = Input::get('oauth_nonce');

		//verifica si el nonce es válido, devuelve string true false
		$vnonce = Nonce::pass($oauth_nonce);
		//$vnonce = Nonce::exist($oauth_nonce);

		//fecha
		$oauth_timestamp = (float)Input::get('oauth_timestamp');
		$diff = Carbon::now()->diffInSeconds(Carbon::createFromTimeStamp($oauth_timestamp))<2?"true":"false";


		//signature
		$oauth_signature = Input::get('oauth_signature');

		//fullname
		$name_full = Input::get('lis_person_name_full');

		$method = Input::get('oauth_signature_method');

		
		unset($_POST['oauth_signature']);
		$arrayvars = $_POST;
		$headers = apache_request_headers();
		
		//foreach ($headers as $key => $value) {
		//	$arrayvars[$key] = urlencode($headers['']);
		//}

		$arrayvars["Host"] = urlencode($headers["Host"]);
		$arrayvars["Content-Type"] = urlencode($headers["Content-Type"]);

		uksort($arrayvars, "strnatcasecmp");
		
		$var0 = http_build_query($arrayvars);

		$vars = rawurldecode($var0);
		
		$key = "834fed55159ae9b7b08ce2b1023c0659";

		$hash =base64_encode( hash_hmac ('sha1', $vars, $key, true) );


		//return print_r($headers);
		return $hash." ".$oauth_signature;

		*/

	}



}

?>