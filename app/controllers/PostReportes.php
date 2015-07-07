<?php

class PostReportes{


	public static function rezagados()
	{
		$return = array();
		if(Rol::hasPermission("rezagados")){

			$q = Rezagado::join('students', 'students.id', '=', 'rezagados.student_id');


			if(isset($_POST['per'])){
				if(!empty($_POST['per'])){
					$q = $q->where('periodo','LIKE','%'.$_POST['per'].'%');
				}
			}

			if(isset($_POST['a1'])){
				if(!empty($_POST['a1'])){
					$name = $_POST['a1'];
					$q = $q->where(function ($query) use ($name) {
					            $query->where('students.name','LIKE','%'.$name.'%')
					                  ->orWhere('students.surname','LIKE','%'.$name.'%'); 
					        });
				}
			}


			$q = $q->with('student');
			$q = $q->with('subject');
			$q = $q->get();
			

			$array = array();
			foreach ($q as $rez) {

				$student = $rez->student;
				$subject = $rez->subject;

				$array[$rez->id] = array();

				if(!empty($subject)){
					$guia = $subject->guia;
					$array[$rez->id]['tema'] = $subject->subject;
				
					if(!empty($guia)){
						$array[$rez->id]['pg'] = $guia->name." ".$guia->surname;
					}

					$pres = $subject->comision()->where("comisions.type","1")->first();
					if(!empty($pres)){
						$array[$rez->id]['pr'] = $pres->name." ".$pres->surname;
					}
					$inv = $subject->comision()->where("comisions.type","2")->first();
					if(!empty($inv)){
						$array[$rez->id]['in'] = $inv->name." ".$inv->surname;
					}

					$tareas = Tarea::wherePeriodo_name($subject->periodo)->where("tipo","<",4)->get();
					$idtareas = array();
					foreach ($tareas as $tarea) {
						$idtareas[] = $tarea->id;
					}

					$nstudent = $student->wc_id==$subject->student1?0:1;

					$sum = 0;
					$n = 0;

					foreach ($idtareas as $tareaid) {
						# code...
						$nota = Nota::whereSubject_id($subject->id)->whereTarea_id($tareaid)->get();
						if(!$nota->isEmpty()){
							$notita = $nota->first();
							$notas = $notita->nota;
							$feedbacks = $notita->feedback;

							if($notas!=""){
                                $notas = json_decode($notas);
                                $notaa = $notas[$nstudent];
                                if(!empty($notaa)){
                                	$sum +=$notaa;
                                	$n++;
                                }
                            }

						}
					}

					if($n>0){
						$array[$rez->id]['pa1'] = ($sum/$n)." (".$n." notas)";
					}else{
						$array[$rez->id]['pa1'] = "(sin notas)";
					}

				}

				$array[$rez->id]['id'] = $rez->id;
				$array[$rez->id]['sem'] = $rez->periodo;
				$array[$rez->id]['status'] = $rez->status;
				$array[$rez->id]['ver'] = $rez->status;

				if(empty($student)){
					$array[$rez->id]['run'] = "";
					$array[$rez->id]['name'] = "";
				}else{
					$array[$rez->id]['run'] = $student->run;
					$array[$rez->id]['a1'] = $student->name." ".$student->surname;
				}

			}

			$return['rows'] = $array;

		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);
	}

	public static function filtro()//memorias historicas
	{
		$return = array();	

		if(Rol::hasPermission("reportes-t-h")){

			$subjs = "";

			$return = array("rows"=>array());
			$active = Periodo::active();
			$subjs = Subject::where('periodo',"!=",$active);	

			if(isset($_POST['sem'])){
				if(!empty($_POST['sem'])){
					if(empty($subjs)){
						$subjs = Subject::where('periodo',"LIKE","%".$_POST['sem']."%");
					}else{
						$subjs->where('periodo',"LIKE","%".$_POST['sem']."%");
					}
				}
			}

			if(isset($_POST['tema'])){
				if(!empty($_POST['tema'])){
					if(empty($subjs)){
						$subjs = Subject::where('subject',"LIKE","%".$_POST['tema']."%");
					}else{
						$subjs->where('subject',"LIKE","%".$_POST['tema']."%");
					}
				}
			}

			if(isset($_POST['a1'])){
				if(!empty($_POST['a1'])){
					if(empty($subjs)){
						$alumno = $_POST['a1'];
						$subjs = Subject::select('subjects.*')->join('students as s1', 's1.wc_id', '=', 'subjects.student1')
										->where(function ($query) use ($alumno) {
							            $query->where('s1.name','LIKE','%'.$alumno.'%')
							                  ->orWhere('s1.surname','LIKE','%'.$alumno.'%')
							                  ->orWhere('s1.wc_id','LIKE','%'.$alumno.'%'); 
							        });
					}else{
						$alumno = $_POST['a1'];
						$subjs = $subjs->select('subjects.*')->join('students as s1', 's1.wc_id', '=', 'subjects.student1')
									->where(function ($query) use ($alumno) {
						            $query->where('s1.name','LIKE','%'.$alumno.'%')
						                  ->orWhere('s1.surname','LIKE','%'.$alumno.'%')
						                  ->orWhere('s1.wc_id','LIKE','%'.$alumno.'%');
						        });
					}
				}
			}

			if(isset($_POST['a2'])){
				if(!empty($_POST['a2'])){
					if(empty($subjs)){
						$alumno = $_POST['a2'];
						$subjs = Subject::select('subjects.*')->join('students as s2', 's2.wc_id', '=', 'subjects.student2')
										->where(function ($query) use ($alumno) {
							            $query->where('s2.name','LIKE','%'.$alumno.'%')
							                  ->orWhere('s2.surname','LIKE','%'.$alumno.'%')
							                  ->orWhere('s2.wc_id','LIKE','%'.$alumno.'%'); 
							        });
					}else{
						$alumno = $_POST['a2'];
						$subjs = $subjs->select('subjects.*')->join('students as s2', 's2.wc_id', '=', 'subjects.student2')
									->where(function ($query) use ($alumno) {
						            $query->where('s2.name','LIKE','%'.$alumno.'%')
						                  ->orWhere('s2.surname','LIKE','%'.$alumno.'%')
						                  ->orWhere('s2.wc_id','LIKE','%'.$alumno.'%');
						        });
					}
				}
			}

			if(isset($_POST['pg'])){
				if(!empty($_POST['pg'])){
					if(empty($subjs)){
						$pg = $_POST['pg'];
						$subjs = Subject::select('subjects.*')->join('staffs', 'staffs.wc_id', '=', 'subjects.adviser')
										->where(function ($query) use ($pg) {
							            $query->where('staffs.name','LIKE','%'.$pg.'%')
							                  ->orWhere('staffs.surname','LIKE','%'.$pg.'%')
							                  ->orWhere('staffs.wc_id','LIKE','%'.$pg.'%'); 
							        });
					}else{
						$pg = $_POST['pg'];
						$subjs = $subjs->select('subjects.*')->join('staffs', 'staffs.wc_id', '=', 'subjects.adviser')
									->where(function ($query) use ($pg) {
						            $query->where('staffs.name','LIKE','%'.$pg.'%')
						                  ->orWhere('staffs.surname','LIKE','%'.$pg.'%')
						                  ->orWhere('staffs.wc_id','LIKE','%'.$pg.'%');
						        });
					}
				}
			}

			if(isset($_POST['cat'])){
				if(!empty($_POST['cat'])){
					if(empty($subjs)){
						$subjs = Subject::select('subjects.*')
								->join('categorias', 'categorias.subject_id', '=', 'subjects.id')
								->where('categorias.categoria',"LIKE",'%'.$_POST['cat'].'%');
					}else{
						$subjs = Subject::select('subjects.*')
								->join('categorias', 'categorias.subject_id', '=', 'subjects.id')
								->where('categorias.categoria',"LIKE",'%'.$_POST['cat'].'%');
					}
				}
			}


			if(!empty($subjs)){

				$subjs = $subjs->with('ostudent1');
				$subjs = $subjs->with('ostudent2');
				$subjs = $subjs->with('guia');
				$subjs = $subjs->with('sponsor');


				$subjs = $subjs->get();
			
				foreach ($subjs as $subj) {
					
					$return["rows"][$subj->id] = array();
					$return["rows"][$subj->id]['id'] = $subj->id;
					$return["rows"][$subj->id]['sem'] = $subj->periodo;
					$return["rows"][$subj->id]['tema'] = $subj->subject;
					$return["rows"][$subj->id]['pg'] = $subj->adviser;
					$return["rows"][$subj->id]['a1'] = $subj->student1;
					$return["rows"][$subj->id]['a2'] = $subj->student2;

					$pres = $subj->comision()->where("comisions.type","1")->first();
					if(!empty($pres)){
						$return["rows"][$subj->id]['pr'] = $pres->name." ".$pres->surname;
					}
					$inv = $subj->comision()->where("comisions.type","2")->first();
					if(!empty($inv)){
						$return["rows"][$subj->id]['in'] = $inv->name." ".$inv->surname;
					}

					$tareas = Tarea::wherePeriodo_name($subj->periodo)->where("tipo","<",4)->get();
					$idtareas = array();
					foreach ($tareas as $tarea) {
						$idtareas[] = $tarea->id;
					}

					if(!empty($subj->ostudent1)){
						$return["rows"][$subj->id]['a1'] = $subj->ostudent1->name." ".$subj->ostudent1->surname;
						$return["rows"][$subj->id]['ea1'] = $subj->ostudent1->status;
						/*$s1 = $subj->ostudent1->expediente;
						if(!empty($s1)){
							$return["rows"][$subj->id]['pa1'] = $s1->promedio;
							$return["rows"][$subj->id]['ea1'] = $s1->estado;
						}*/

					}
					if(!empty($subj->ostudent2)){
						$return["rows"][$subj->id]['a2'] = $subj->ostudent2->name." ".$subj->ostudent2->surname;
						$return["rows"][$subj->id]['ea2'] = $subj->ostudent2->status;
						/*$s2 = $subj->ostudent2->expediente;
						if(!empty($s2)){
							$return["rows"][$subj->id]['pa2'] = $s2->promedio;
							$return["rows"][$subj->id]['ea2'] = $s2->estado;
						}*/
					}

					$sum1 = 0;
					$n1 = 0;
					$sum2 = 0;
					$n2 = 0;

					foreach ($idtareas as $tareaid) {
						# code...
						$nota = Nota::whereSubject_id($subj->id)->whereTarea_id($tareaid)->get();
						if(!$nota->isEmpty()){
							$notita = $nota->first();
							$notas = $notita->nota;
							$feedbacks = $notita->feedback;

							if($notas!=""){
                                $notas = json_decode($notas);
                                $nota1 = $notas[0];
                                $nota2 = $notas[1];
                                if(!empty($nota1)){
                                	$sum1 +=$nota1;
                                	$n1++;
                                }
                                if(!empty($nota2)){
                                	$sum2 +=$nota2;
                                	$n2++;
                                }
                            }

						}
					}

					if($n1>0){
						$return["rows"][$subj->id]['pa1'] = ($sum1/$n1)." (".$n1." notas)";
					}else{
						$return["rows"][$subj->id]['pa1'] = "(sin notas)";
					}
					if($n2>0){
						$return["rows"][$subj->id]['pa2'] = ($sum2/$n2)." (".$n2." notas)";
					}else{
						$return["rows"][$subj->id]['pa2'] = "(sin notas)";
					}

					if(!empty($subj->guia)){
						$return["rows"][$subj->id]['pg'] = $subj->guia->name." ".$subj->guia->surname;
					}

					if(!empty($subj->sponsor)){
						$return["rows"][$subj->id]['em'] = $subj->sponsor->razon;
					}

					$cats = $subj->categorias;
					$i1 = 0;
					$return["rows"][$subj->id]['cat'] = "";
					foreach ($cats as $cat) {
						if($i1>0){$return["rows"][$subj->id]['cat'] .=", ";}
						$i1++;
						$return["rows"][$subj->id]['cat'] .= $cat->categoria;
					}

				}

			}

		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);
	}

	public static function filtroactivo()
	{
		$return = array();	

		if(Rol::hasPermission("reportes-t")){

			$subjs = "";

			$return = array("rows"=>array());	

			$active = Periodo::active();
			$subjs = Subject::where('periodo',$active);

			$tareas = Tarea::wherePeriodo_name($active)->where("tipo","<",4)->get();
			$idtareas = array();
			foreach ($tareas as $tarea) {
				$idtareas[] = $tarea->id;
			}

			if(isset($_POST['tema'])){
				if(!empty($_POST['tema'])){
					if(empty($subjs)){
						$subjs = Subject::where('subject',"LIKE","%".$_POST['tema']."%");
					}else{
						$subjs->where('subject',"LIKE","%".$_POST['tema']."%");
					}
				}
			}

			if(isset($_POST['a1'])){
				if(!empty($_POST['a1'])){
					if(empty($subjs)){
						$alumno = $_POST['a1'];
						$subjs = Subject::select('subjects.*')->join('students as s1', 's1.wc_id', '=', 'subjects.student1')
										->where(function ($query) use ($alumno) {
							            $query->where('s1.name','LIKE','%'.$alumno.'%')
							                  ->orWhere('s1.surname','LIKE','%'.$alumno.'%')
							                  ->orWhere('s1.wc_id','LIKE','%'.$alumno.'%'); 
							        });
					}else{
						$alumno = $_POST['a1'];
						$subjs = $subjs->select('subjects.*')->join('students as s1', 's1.wc_id', '=', 'subjects.student1')
									->where(function ($query) use ($alumno) {
						            $query->where('s1.name','LIKE','%'.$alumno.'%')
						                  ->orWhere('s1.surname','LIKE','%'.$alumno.'%')
						                  ->orWhere('s1.wc_id','LIKE','%'.$alumno.'%');
						        });
					}
				}
			}

			if(isset($_POST['a2'])){
				if(!empty($_POST['a2'])){
					if(empty($subjs)){
						$alumno = $_POST['a2'];
						$subjs = Subject::select('subjects.*')->join('students as s2', 's2.wc_id', '=', 'subjects.student2')
										->where(function ($query) use ($alumno) {
							            $query->where('s2.name','LIKE','%'.$alumno.'%')
							                  ->orWhere('s2.surname','LIKE','%'.$alumno.'%')
							                  ->orWhere('s2.wc_id','LIKE','%'.$alumno.'%'); 
							        });
					}else{
						$alumno = $_POST['a2'];
						$subjs = $subjs->select('subjects.*')->join('students as s2', 's2.wc_id', '=', 'subjects.student2')
									->where(function ($query) use ($alumno) {
						            $query->where('s2.name','LIKE','%'.$alumno.'%')
						                  ->orWhere('s2.surname','LIKE','%'.$alumno.'%')
						                  ->orWhere('s2.wc_id','LIKE','%'.$alumno.'%');
						        });
					}
				}
			}

			if(isset($_POST['pg'])){
				if(!empty($_POST['pg'])){
					if(empty($subjs)){
						$pg = $_POST['pg'];
						$subjs = Subject::select('subjects.*')->join('staffs', 'staffs.wc_id', '=', 'subjects.adviser')
										->where(function ($query) use ($pg) {
							            $query->where('staffs.name','LIKE','%'.$pg.'%')
							                  ->orWhere('staffs.surname','LIKE','%'.$pg.'%')
							                  ->orWhere('staffs.wc_id','LIKE','%'.$pg.'%'); 
							        });
					}else{
						$pg = $_POST['pg'];
						$subjs = $subjs->select('subjects.*')->join('staffs', 'staffs.wc_id', '=', 'subjects.adviser')
									->where(function ($query) use ($pg) {
						            $query->where('staffs.name','LIKE','%'.$pg.'%')
						                  ->orWhere('staffs.surname','LIKE','%'.$pg.'%')
						                  ->orWhere('staffs.wc_id','LIKE','%'.$pg.'%');
						        });
					}
				}
			}

			if(isset($_POST['cat'])){
				if(!empty($_POST['cat'])){
					if(empty($subjs)){
						$subjs = Subject::select('subjects.*')
								->join('categorias', 'categorias.subject_id', '=', 'subjects.id')
								->where('categorias.categoria',"LIKE",'%'.$_POST['cat'].'%');
					}else{
						$subjs = Subject::select('subjects.*')
								->join('categorias', 'categorias.subject_id', '=', 'subjects.id')
								->where('categorias.categoria',"LIKE",'%'.$_POST['cat'].'%');
					}
				}
			}


			if(!empty($subjs)){

				$subjs = $subjs->with('ostudent1');
				$subjs = $subjs->with('ostudent2');
				$subjs = $subjs->with('guia');
				$subjs = $subjs->with('sponsor');


				$subjs = $subjs->get();
			
				foreach ($subjs as $subj) {
					
					$return["rows"][$subj->id] = array();

					$return["rows"][$subj->id]['id'] = $subj->id;
					$return["rows"][$subj->id]['sem'] = $subj->periodo;
					$return["rows"][$subj->id]['tema'] = $subj->subject;
					$return["rows"][$subj->id]['pg'] = $subj->adviser;
					$return["rows"][$subj->id]['a1'] = $subj->student1;
					$return["rows"][$subj->id]['a2'] = $subj->student2;

					if(!empty($subj->ostudent1)){
						$return["rows"][$subj->id]['a1'] = $subj->ostudent1->name." ".$subj->ostudent1->surname;
						$s1 = $subj->ostudent1->expediente;
						if(!empty($s1)){
							$return["rows"][$subj->id]['pa1'] = $s1->promedio;
							$return["rows"][$subj->id]['ea1'] = $s1->estado;
						}

					}
					if(!empty($subj->ostudent2)){
						$return["rows"][$subj->id]['a2'] = $subj->ostudent2->name." ".$subj->ostudent2->surname;
						$s2 = $subj->ostudent2->expediente;
						if(!empty($s2)){
							$return["rows"][$subj->id]['pa2'] = $s2->promedio;
							$return["rows"][$subj->id]['ea2'] = $s2->estado;
						}
					}
					if(!empty($subj->guia)){
						$return["rows"][$subj->id]['pg'] = $subj->guia->name." ".$subj->guia->surname;
					}

					if(!empty($subj->sponsor)){
						$return["rows"][$subj->id]['em'] = $subj->sponsor->razon;
					}

					$cats = $subj->categorias;
					$i1 = 0;
					$return["rows"][$subj->id]['cat'] = "";
					foreach ($cats as $cat) {
						if($i1>0){$return["rows"][$subj->id]['cat'] .=", ";}
						$i1++;
						$return["rows"][$subj->id]['cat'] .= $cat->categoria;
					}


					//$nstudent = $subj->wc_id==$subj->subject->student1?0:1;

					$sum1 = 0;
					$n1 = 0;
					$sum2 = 0;
					$n2 = 0;

					foreach ($idtareas as $tareaid) {
						# code...
						$nota = Nota::whereSubject_id($subj->id)->whereTarea_id($tareaid)->get();
						if(!$nota->isEmpty()){
							$notita = $nota->first();
							$notas = $notita->nota;
							$feedbacks = $notita->feedback;

							if($notas!=""){
                                $notas = json_decode($notas);
                                $nota1 = $notas[0];
                                $nota2 = $notas[1];
                                if(!empty($nota1)){
                                	$sum1 +=$nota1;
                                	$n1++;
                                }
                                if(!empty($nota2)){
                                	$sum2 +=$nota2;
                                	$n2++;
                                }
                            }

						}
					}

					if($n1>0){
						$return["rows"][$subj->id]['pa1'] = ($sum1/$n1)." (".$n1." notas)";
					}else{
						$return["rows"][$subj->id]['pa1'] = "(sin notas)";
					}
					if($n2>0){
						$return["rows"][$subj->id]['pa2'] = ($sum2/$n2)." (".$n2." notas)";
					}else{
						$return["rows"][$subj->id]['pa2'] = "(sin notas)";
					}

				}

			}

		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);
	}

	public static function filtroporalumnos()
	{
		$return = array();	

		if(Rol::hasPermission("reportes-a")){

			$active = Periodo::active();
			$tareas = Tarea::wherePeriodo_name($active)->where("tipo","<",4)->get();
			$idtareas = array();
			foreach ($tareas as $tarea) {
				$idtareas[] = $tarea->id;
			}

			$student = Student::select('students.*')
						->join('subjects', 'subjects.id', '=', 'students.subject_id')
						->where('subjects.periodo',$active);

			$return = array("rows"=>array());	



			if(isset($_POST['run'])){
				if(!empty($_POST['run'])){
					$alumno = $_POST['run'];
					$student = $student->where('students.run','LIKE','%'.$alumno.'%'); 
				}
			}

			if(isset($_POST['a1'])){
				if(!empty($_POST['a1'])){
					$alumno = $_POST['a1'];
					$student = $student->where(function ($query) use ($alumno) {
					            $query->where('students.name','LIKE','%'.$alumno.'%')
					                  ->orWhere('students.surname','LIKE','%'.$alumno.'%'); 
					        });
				}
			}

			if(isset($_POST['mail'])){
				if(!empty($_POST['mail'])){
					$alumno = $_POST['mail'];
					$student = $student->where('students.wc_id','LIKE','%'.$alumno.'%'); 
				}
			}

			if(isset($_POST['tema'])){
				if(!empty($_POST['tema'])){
					$tema = $_POST['tema'];
					$student = $student->where('subjects.subject','LIKE','%'.$tema.'%'); 
				}
			}

			if(isset($_POST['pg'])){
				if(!empty($_POST['pg'])){
					$guia = $_POST['pg'];
					$student = $student->join('staffs', 'subjects.adviser', '=', 'staffs.wc_id')
								->where(function ($query) use ($guia) {
					            $query->where('staffs.name','LIKE','%'.$guia.'%')
					                  ->orWhere('staffs.surname','LIKE','%'.$guia.'%'); 
					        });
				}
			}

			if(isset($_POST['car'])){
				if(!empty($_POST['car'])){
					$car = $_POST['car'];
					$student = $student->join('expedientes', 'expedientes.student_id', '=', 'students.id')
								->where('expedientes.carrera','LIKE','%'.$car.'%');
				}
			}


			if(!empty($student)){

				/*$subjects = $subjects->with('ostudent1');
				$subjects = $subjects->with('ostudent2');
				$subjects = $subjects->with('guia');
				$subjects = $subjects->with('sponsor');*/

				$student = $student->with('subject');
				$student = $student->with('expediente');

				$student = $student->get();
			
				foreach ($student as $row) {
					
					$return["rows"][$row->id] = array();

					//$return["rows"][$row->id]['sem'] = $row->periodo;
					//$return["rows"][$row->id]['tema'] = $row->subject;
					//$return["rows"][$row->id]['pg'] = $row->adviser;
					$return["rows"][$row->id]['id'] = $row->id;
					$return["rows"][$row->id]['run'] = $row->run;
					$return["rows"][$row->id]['a1'] = $row->name." ".$row->surname;
					$return["rows"][$row->id]['mail'] = $row->wc_id;


					if(!empty($row->expediente)){
						$return["rows"][$row->id]['car'] = $row->expediente->carrera;
						$return["rows"][$row->id]['fin'] = $row->expediente->financiero;
						$return["rows"][$row->id]['bib'] = $row->expediente->biblioteca;
						$return["rows"][$row->id]['aca'] = $row->expediente->academico;
					}

					if(!empty($row->subject)){
						$return["rows"][$row->id]['tema'] = $row->subject->subject;
						$s1 = $row->subject->guia;
						if(!empty($s1)){
							$return["rows"][$row->id]['pg'] = $s1->name." ".$s1->surname;
						}

						$nstudent = $row->wc_id==$row->subject->student1?0:1;

						$sum = 0;
						$n = 0;

						foreach ($idtareas as $tareaid) {
							# code...
							$nota = Nota::whereSubject_id($row->subject->id)->whereTarea_id($tareaid)->get();
							if(!$nota->isEmpty()){
								$notita = $nota->first();
								$notas = $notita->nota;
								$feedbacks = $notita->feedback;

								if($notas!=""){
	                                $notas = json_decode($notas);
	                                $notaa = $notas[$nstudent];
	                                if(!empty($notaa)){
	                                	$sum +=$notaa;
	                                	$n++;
	                                }
	                            }

							}
						}

						if($n>0){
							$return["rows"][$row->id]['pa1'] = ($sum/$n)." (".$n." notas)";
						}else{
							$return["rows"][$row->id]['pa1'] = "(sin notas)";
						}

					}/*
					if(!empty($subj->ostudent2)){
						$return["rows"][$row->id]['a2'] = $subj->ostudent2->name." ".$subj->ostudent2->surname;
						$s2 = $subj->ostudent2->expediente;
						if(!empty($s2)){
							$return["rows"][$row->id]['pa2'] = $s2->promedio;
							$return["rows"][$row->id]['ea2'] = $s2->estado;
						}
					}
					if(!empty($subj->guia)){
						$return["rows"][$row->id]['pg'] = $subj->guia->name." ".$subj->guia->surname;
					}

					if(!empty($subj->sponsor)){
						$return["rows"][$row->id]['em'] = $subj->sponsor->razon;
					}

					$cats = $row->categorias;
					$i1 = 0;
					$return["rows"][$row->id]['cat'] = "";
					foreach ($cats as $cat) {
						if($i1>0){$return["rows"][$row->id]['cat'] .=", ";}
						$i1++;
						$return["rows"][$row->id]['cat'] .= $cat->categoria;
					}

					*/

				}

			}

		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);
	}

	public static function filtroporalumnoshist()
	{
		$return = array();	

		if(Rol::hasPermission("reportes-a-h")){

			$active = Periodo::active();

			$student = Student::select('students.*')
						->join('subjects', 'subjects.id', '=', 'students.subject_id')
						->where('subjects.periodo',"!=",$active);

			$return = array("rows"=>array());	



			if(isset($_POST['run'])){
				if(!empty($_POST['run'])){
					$alumno = $_POST['run'];
					$student = $student->where('students.run','LIKE','%'.$alumno.'%'); 
				}
			}

			if(isset($_POST['per'])){
				if(!empty($_POST['per'])){
					$per = $_POST['per'];
					$student = $student->where('subjects.periodo','LIKE','%'.$per.'%'); 
				}
			}

			if(isset($_POST['a1'])){
				if(!empty($_POST['a1'])){
					$alumno = $_POST['a1'];
					$student = $student->where(function ($query) use ($alumno) {
					            $query->where('students.name','LIKE','%'.$alumno.'%')
					                  ->orWhere('students.surname','LIKE','%'.$alumno.'%'); 
					        });
				}
			}

			if(isset($_POST['ea1'])){
				if(!empty($_POST['ea1'])){
					$alumno = $_POST['ea1'];
					$student = $student->where(function ($query) use ($alumno) {
					            $query->where('students.status','LIKE','%'.$alumno.'%'); 
					        });
				}
			}

			if(isset($_POST['mail'])){
				if(!empty($_POST['mail'])){
					$alumno = $_POST['mail'];
					$student = $student->where('students.wc_id','LIKE','%'.$alumno.'%'); 
				}
			}

			if(isset($_POST['tema'])){
				if(!empty($_POST['tema'])){
					$tema = $_POST['tema'];
					$student = $student->where('subjects.subject','LIKE','%'.$tema.'%'); 
				}
			}

			if(isset($_POST['pg'])){
				if(!empty($_POST['pg'])){
					$guia = $_POST['pg'];
					$student = $student->join('staffs', 'subjects.adviser', '=', 'staffs.wc_id')
								->where(function ($query) use ($guia) {
					            $query->where('staffs.name','LIKE','%'.$guia.'%')
					                  ->orWhere('staffs.surname','LIKE','%'.$guia.'%'); 
					        });
				}
			}


			if(!empty($student)){

				/*$subjects = $subjects->with('ostudent1');
				$subjects = $subjects->with('ostudent2');
				$subjects = $subjects->with('guia');
				$subjects = $subjects->with('sponsor');*/

				$student = $student->with('subject');

				$student = $student->get();
			
				foreach ($student as $row) {
					
					$return["rows"][$row->id] = array();

					//$return["rows"][$row->id]['sem'] = $row->periodo;
					//$return["rows"][$row->id]['tema'] = $row->subject;
					//$return["rows"][$row->id]['pg'] = $row->adviser;
					$return["rows"][$row->id]['id'] = $row->id;
					$return["rows"][$row->id]['run'] = $row->run;
					$return["rows"][$row->id]['a1'] = $row->name." ".$row->surname;
					$return["rows"][$row->id]['ea1'] = $row->status;
					$return["rows"][$row->id]['mail'] = $row->wc_id;

					if(!empty($row->subject)){
						$return["rows"][$row->id]['tema'] = $row->subject->subject;
						$return["rows"][$row->id]['per'] = $row->subject->periodo;
						$s1 = $row->subject->guia;
						if(!empty($s1)){
							$return["rows"][$row->id]['pg'] = $s1->name." ".$s1->surname;
						}

					}/*
					if(!empty($subj->ostudent2)){
						$return["rows"][$row->id]['a2'] = $subj->ostudent2->name." ".$subj->ostudent2->surname;
						$s2 = $subj->ostudent2->expediente;
						if(!empty($s2)){
							$return["rows"][$row->id]['pa2'] = $s2->promedio;
							$return["rows"][$row->id]['ea2'] = $s2->estado;
						}
					}
					if(!empty($subj->guia)){
						$return["rows"][$row->id]['pg'] = $subj->guia->name." ".$subj->guia->surname;
					}

					if(!empty($subj->sponsor)){
						$return["rows"][$row->id]['em'] = $subj->sponsor->razon;
					}

					$cats = $row->categorias;
					$i1 = 0;
					$return["rows"][$row->id]['cat'] = "";
					foreach ($cats as $cat) {
						if($i1>0){$return["rows"][$row->id]['cat'] .=", ";}
						$i1++;
						$return["rows"][$row->id]['cat'] .= $cat->categoria;
					}

					*/

				}

			}

		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);
	}

	public static function hoja()
	{
		$return = array();	

		if(Rol::hasPermission("reportes-hoja")){

			$active = Periodo::active();

			$student = Student::select('students.*')
						->join('subjects', 'subjects.id', '=', 'students.subject_id')
						->where('subjects.periodo',$active);

			$return = array("rows"=>array());	

			if(isset($_POST['a1'])){
				if(!empty($_POST['a1'])){
					$alumno = $_POST['a1'];
					$student = $student->where(function ($query) use ($alumno) {
					            $query->where('students.name','LIKE','%'.$alumno.'%')
					                  ->orWhere('students.surname','LIKE','%'.$alumno.'%')
					                  ->orWhere('students.run','LIKE','%'.$alumno.'%')
					                  ->orWhere('students.wc_id','LIKE','%'.$alumno.'%'); 
					        });
				}
			}

			if(!empty($student)){

				$student = $student->with('subject');

				$student = $student->get();
			
				foreach ($student as $row) {
					
					$return["rows"][$row->id] = array();
					$return["rows"][$row->id]['a1'] = $row->name." ".$row->surname;



					if(!empty($row->subject)){
						$return["rows"][$row->id]['tema'] = $row->subject->subject;

						$nstudent = $row->wc_id==$row->subject->student1?0:1;
						

						$hoja = $row->subject->firmas;
						if(!empty($hoja)){

							if($nstudent==0){
								$return['rows'][$row->id]['fa'] = $hoja->student1=="firmado"?"1":($hoja->student1=="rechazado"?"0":"");
								$return['rows'][$row->id]['fpg'] = $hoja->adviser=="firmado"?"1":($hoja->adviser=="rechazado"?"0":"");
								$return['rows'][$row->id]['fra'] = $hoja->revisor=="firmado"?"1":($hoja->revisor=="rechazado"?"0":"");
								$return['rows'][$row->id]['fsa'] = $hoja->secre1=="firmado"?"1":($hoja->secre1=="rechazado"?"0":"");
							}

							if($nstudent==1){
								$return['rows'][$row->id]['fa'] = $hoja->student2=="firmado"?"1":($hoja->student2=="rechazado"?"0":"");
								$return['rows'][$row->id]['fpg'] = $hoja->adviser=="firmado"?"1":($hoja->adviser=="rechazado"?"0":"");
								$return['rows'][$row->id]['fra'] = $hoja->revisor=="firmado"?"1":($hoja->revisor=="rechazado"?"0":"");
								$return['rows'][$row->id]['fsa'] = $hoja->secre2=="firmado"?"1":($hoja->secre2=="rechazado"?"0":"");
							}


						}




					}

				}

			}

		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);
	}

	public static function evaluaciones()
	{
		$return = array();	

		if(Rol::hasPermission("reportes-eval")){

			$active = Periodo::active();
			$tareas = Tarea::wherePeriodo_name($active)->where("tipo","<",4)->get();
			$return["rows"] = array();
			
			$ntot = 0;
			$subjs = Subject::wherePeriodo($active)->get();
			foreach ($subjs as $subj) {
				if(!empty($subj->student1)){
					$ntot++;
				}
				if(!empty($subj->student2)){
					$ntot++;
				}
			}

			foreach ($tareas as $tarea) {
				
				$return["rows"][$tarea->id] = array();
				$return["rows"][$tarea->id]['id'] = $tarea->id;
				$return["rows"][$tarea->id]['name'] = $tarea->title;
				$return["rows"][$tarea->id]['fecha'] = $tarea->date;

				$fecha = Carbon::parse($tarea->date);
				$plazo = ($fecha>Carbon::now()->subDays($tarea->evaltime) && $fecha<Carbon::now())?"":"1";

				$ntarea = 0;
				$notas = Nota::whereTarea_id($tarea->id)->get();
				foreach ($notas as $nota) {
					$notasjs = $nota->nota;

					if($notasjs!=""){
                        $notasjs = json_decode($notasjs);
                        $nota1 = $notasjs[0];
                        $nota2 = $notasjs[1];
                        
                        if(!empty($nota1)){
                        	$ntarea++;
                        }
                        if(!empty($nota2)){
                        	$ntarea++;
                        }
                    }
				}

				$return["rows"][$tarea->id]['n'] = $ntarea." / ".$ntot;

				$return["rows"][$tarea->id]['plazo'] = $plazo;
				
				$return["rows"][$tarea->id]['ver'] = $tarea->title;

			}

		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);
	}

	public static function evaluacionestarea()
	{
		$return = array();	

		if(Rol::hasPermission("reportes-eval")){

			if(isset($_POST['idtarea'])){

			$tarea = Tarea::find($_POST['idtarea']);
			$fecha = Carbon::parse($tarea->date);
			$estado = 0;
			if($fecha<Carbon::now()){//ya se entregó
				if($fecha>Carbon::now()->subDays($tarea->evaltime)){//en periodo para evaluar
					//check, vacio
					$estado = 1;
				}else{//ya pasó el periodo de evaluacion
					//check, cruz
					$estado = 2;
				}
			}else{//no se ha entregado
				//vacio
				$estado = 0;
			}

			$active = Periodo::active();
			$subjs = Subject::select("subjects.*")->wherePeriodo($active);

			if(isset($_POST['tema'])){
				if(!empty($_POST['tema'])){
					$tema = $_POST['tema'];
					$subjs = $subjs->where('subjects.subject','LIKE','%'.$tema.'%'); 
				}
			}

			if(isset($_POST['pg'])){
				if(!empty($_POST['pg'])){
					$guia = $_POST['pg'];
					$subjs = $subjs->join('staffs', 'subjects.adviser', '=', 'staffs.wc_id')
								->where(function ($query) use ($guia) {
					            $query->where('staffs.name','LIKE','%'.$guia.'%')
					                  ->orWhere('staffs.surname','LIKE','%'.$guia.'%'); 
					        });
				}
			}

			$subjs = $subjs->get();

			$return["rows"] = array();
			foreach ($subjs as $subj) {
				
				$return["rows"][$subj->id] = array();
				$return["rows"][$subj->id]['id'] = $subj->id;
				$return["rows"][$subj->id]['tema'] = $subj->subject;

				$s1 = $subj->guia;
				if(!empty($s1)){
					$return["rows"][$subj->id]['pg'] = $s1->name." ".$s1->surname;
				}

				$nota = Nota::whereTarea_id($tarea->id)->whereSubject_id($subj->id)->first();

				if(!empty($nota)){

					$notasjs = $nota->nota;
					if($notasjs!=""){
	                    $notasjs = json_decode($notasjs);
	                    $nota1 = $notasjs[0];
	                    $nota2 = $notasjs[1];
	                    
	                    if(!empty($nota1)){
	                    	$return["rows"][$subj->id]['ea1'] = "1" ;
	                    }else{
	                    	if($estado==2){
								$return["rows"][$subj->id]['ea1'] = "0" ;
							}
	                    }
	                    if(!empty($nota2)){
	                    	$return["rows"][$subj->id]['ea2'] = "1" ;
	                    }else{
	                    	if($estado==2){
								$return["rows"][$subj->id]['ea2'] = "0" ;
							}
	                    }
	                }else{
	                	if($estado==2){
							$return["rows"][$subj->id]['ea1'] = "0" ;
							$return["rows"][$subj->id]['ea2'] = "0" ;
						}
	                }

				}else{
					if($estado==2){
						$return["rows"][$subj->id]['ea1'] = "0" ;
						$return["rows"][$subj->id]['ea2'] = "0" ;
					}
				}

			

			}

			}else{
				$return["error"] = "faltan variables";
			}

		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);
	}

	public static function evalguias()
	{
		$return = array();	

		if(Rol::hasPermission("reportes-evalguias")){

			$active = Periodo::active();

			$evaluacion = Evalguia::select('evalguias.*')
						->join('staffs', 'staffs.id', '=', 'evalguias.pg')
						->where('evalguias.periodo', "!=", $active)
						->where('evalguias.subject_id', "");

			$return = array("rows"=>array());	

			if(isset($_POST['sem'])){
				if(!empty($_POST['sem'])){
					$sem = $_POST['sem'];
					$evaluacion = $evaluacion->where('evalguias.periodo','LIKE','%'.$sem.'%');
				}
			}

			if(isset($_POST['prom'])){
				if(!empty($_POST['prom'])){
					$prom = $_POST['prom'];
					$evaluacion = $evaluacion->where('evalguias.promedio','LIKE','%'.$prom.'%');
				}
			}

			if(isset($_POST['pg'])){
				if(!empty($_POST['pg'])){
					$guia = $_POST['pg'];
					$evaluacion = $evaluacion->where(function ($query) use ($guia) {
					            $query->where('staffs.name','LIKE','%'.$guia.'%')
					                  ->orWhere('staffs.surname','LIKE','%'.$guia.'%'); 
					        });
				}
			}

			if(!empty($evaluacion)){

				$evaluacion = $evaluacion->with('guia');

				$evaluacion = $evaluacion->get();
			
				foreach ($evaluacion as $row) {
					
					$return["rows"][$row->id] = array();
					$return["rows"][$row->id]['sem'] = $row->periodo;

					if(!empty($row->guia)){
						$return["rows"][$row->id]['pg'] = $row->guia->name." ".$row->guia->surname;
					}

					$return["rows"][$row->id]['prom'] = round($row->promedio,1);

				}

			}

		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);
	}

	public static function gmtdata()
	{
		$return = array();	
		if(Rol::actual("SA")){
			if(isset($_POST['id'])){

				$subj = Subject::find($_POST['id']);
				if(!empty($subj)){
					$return['subject'] = $subj->subject;
					$return['adviser'] = $subj->adviser;
					$return['status'] = $subj->status;
					$return['periodo'] = $subj->periodo;
					$return['student1'] = $subj->student1;
					$return['student2'] = $subj->student2;

					$pres = $subj->comision()->where("comisions.type","1")->first();
					if(!empty($pres)){
						$return['pr'] = $pres->wc_id;
					}else{
						$return['pr'] = "";
					}
					$inv = $subj->comision()->where("comisions.type","2")->first();
					if(!empty($inv)){
						$return['in'] = $inv->wc_id;
					}else{
						$return['in'] = "";
					}

					$return['pre'] = "";
					$return['def'] = "";
					$defensas = CEvent::whereDetail($subj->id)->get();
					if(!$defensas->isEmpty()){
						foreach ($defensas as $event) {
							if($event->color=="blue"){
								$return['def'] = CarbonLocale::spanish(Carbon::parse($event->start)->formatLocalized('%A %d de %B de %Y a las %H:%M'));
							}elseif($event->color=="darkcyan"){
								$return['pre'] = CarbonLocale::spanish(Carbon::parse($event->start)->formatLocalized('%A %d de %B de %Y a las %H:%M'));
							}
						}
					}

				}else{
					$return["error"] = "Proyecto no existe";
				}
			}else{
				$return["error"] = "faltan variables";	
			}
		}else{
			$return["error"] = "not permission";
		}
		return json_encode($return);
	}

	public static function gmadata()
	{
		$return = array();	
		if(Rol::actual("SA")){
			if(isset($_POST['id'])){

				$student = Student::find($_POST['id']);
				if(!empty($student)){
					$return['wc_id'] = $student->wc_id;
					$return['run'] = $student->run;
					$return['name'] = $student->name;
					$return['surname'] = $student->surname;
					$return['status'] = $student->status;
					$return['subject'] = $student->student2;



				}else{
					$return["error"] = "Proyecto no existe";
				}
			}else{
				$return["error"] = "faltan variables";	
			}
		}else{
			$return["error"] = "not permission";
		}
		return json_encode($return);
	}

}