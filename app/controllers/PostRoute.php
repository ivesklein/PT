<?php

class PostRoute{
	
	public static function temas()
	{	

		if(Auth::check()){

			$TEMA = 0 ;
			$RUN1 = 1 ;
			$NOMBRE1 = 2 ;
			$APELLIDO1 = 3 ;
			$EMAIL1 = 4 ;
			$RUN2 = 5 ;
			$NOMBRE2 = 6 ;
			$APELLIDO2 = 7 ;
			$EMAIL2 = 8 ;
			$NPROFESOR = 9 ;
			$APROFESOR = 10 ;
			$MPROFESOR = 11 ;

			$periodo = $_POST['periodo'];


			$file = Files::post("csv");

			if(isset($file["ok"])){
				$ruta = $file["ok"]["tmp_name"];
				
				//return $file["ok"]["type"];
				
				$res = CSV::toArray($ruta);

				//for profesores, 
					//verificar si existen, 
					//si no crearlos.
				$profesores = array();
				foreach ($res as $n => $fila) {
					if($n!=0){

						//verificar solidez de los datos

						//usar los datos
						//si no está el profesor
						if(!isset($profesores[$fila[$MPROFESOR]])){

							//verificar que existe en db
							$profedb = User::whereWc_id($fila[$MPROFESOR])->get();
							if(!$profedb->isEmpty()){
								//agregar
								$proferow = $profedb->first();
								$profesores[$proferow->wc_id] = $proferow->pm_uid;
								//guardar datos para operaciones siguientes
							}else{
							//sino

								//crear
								$res2 = UserCreation::add(
									$fila[$MPROFESOR],
									$fila[$NPROFESOR],
									$fila[$APROFESOR],
									"P");

								if(isset($res2["ok"])){
									$profesores[$res2["ok"]["wc"]] = $res2["ok"]["pm"];
									//agregar
									//guardar datos para operaciones siguientes
								}else{
									//error
									print_r($res2["error"]);

								}
							}//if existe
						}//if está
					}//row encabezado
				}//for rows


				//for alumnos
					//si no existe, crearlo
				foreach ($res as $n => $fila) {
					if($n!=0){
						//alumno 1
						$run = $fila[$RUN1];
						$name = $fila[$NOMBRE1];
						$surname = $fila[$APELLIDO1];
						$mail = $fila[$EMAIL1];
						$studentdb = Student::whereWc_id($mail)->get();
						if($studentdb->isEmpty()){
							$student = new Student;
							$student->wc_id = $mail;
							$student->run = $run;
							$student->name = $name; 
							$student->surname = $surname; 
							$student->save();
						}

						//alumno 2
						$run = $fila[$RUN2];
						$name = $fila[$NOMBRE2];
						$surname = $fila[$APELLIDO2];
						$mail = $fila[$EMAIL2];
						if($studentdb->isEmpty()){
							$student = new Student;
							$student->wc_id = $mail;
							$student->run = $run;
							$student->name = $name; 
							$student->surname = $surname; 
							$student->save();
						}
					}
				}

				//for temas
					//registrar
				foreach ($res as $n => $fila) {
					if($n!=0){

						$pm = new PMsoap;
						$res = $pm->login();
						if(isset($res['ok'])){
							$res2 = $pm->newTema($fila[$TEMA], $fila[$EMAIL1], $fila[$EMAIL2], $fila[$MPROFESOR]);
							if(isset($res2["ok"])){
								$subj = new Subject;
								$subj->subject = $fila[$TEMA];
								$subj->student1 = $fila[$EMAIL1];
								$subj->student2 = $fila[$EMAIL2];
								$subj->adviser = $fila[$MPROFESOR];
								$subj->status = "start";
								$subj->pm_uid = $res2["ok"]["uid"];
								$subj->save();
							}else{
								//error
							}


						}else{
							//error
						}
					}
				}

				return "ok";



			}else{
				//error con el archivo
				return var_dump($file["error"]);
			}




		}else{
			return Redirect::to("login");
		}
	}
}

?>