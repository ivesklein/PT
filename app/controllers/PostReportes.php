<?php

class PostReportes{


	public static function rezagados()
	{
		$return = array();
		if(Rol::hasPermission("rezagados")){

			$q = "";

			if(isset($_POST['per'])){
				if(!empty($_POST['per'])){
					if(empty($q)){
						$q = Rezagado::wherePeriodo($_POST['per']);
					}else{
						$q = $q->wherePeriodo($_POST['per']);
					}
				}
			}

			if(isset($_POST['name'])){
				if(!empty($_POST['name'])){
					$name = $_POST['name'];
					if(empty($q)){
						$q = Rezagado::join('students', 'students.id', '=', 'rezagados.student_id')
									->where(function ($query) use ($name) {
						            $query->where('students.name','LIKE','%'.$name.'%')
						                  ->orWhere('students.surname','LIKE','%'.$name.'%'); 
						        });
					}else{
						$q = $q->join('students', 'students.id', '=', 'rezagados.student_id')
									->where(function ($query) use ($name) {
						            $query->where('students.name','LIKE','%'.$name.'%')
						                  ->orWhere('students.surname','LIKE','%'.$name.'%'); 
						        });
					}
				}
			}


			if(empty($q)){
				$q = Rezagado::all();
			}else{
				$q = $q->get();
			}

			$array = array();
			foreach ($q as $rez) {

				$student = $rez->student;
				$subject = $rez->subject;

				if(!empty($subject)){
					$guia = $subject->guia;
				}else{
					$guia="";
				}

				$array[$rez->id] = array();
				$array[$rez->id]['id'] = $rez->id;
				$array[$rez->id]['periodo'] = $rez->periodo;
				$array[$rez->id]['status'] = $rez->status;

				if(empty($student)){
					$array[$rez->id]['run'] = "";
					$array[$rez->id]['name'] = "";
				}else{
					$array[$rez->id]['run'] = $student->run;
					$array[$rez->id]['name'] = $student->name." ".$student->surname;
				}

				if(empty($subject)){
					$array[$rez->id]['subject'] = "";
				}else{
					$array[$rez->id]['subject'] = $subject->subject;
				}

				if(empty($guia)){
					$array[$rez->id]['guia'] = "";
				}else{
					$array[$rez->id]['guia'] = $guia->name." ".$guia->surname;
				}

			}

			$return['ok'] = $array;

		}else{
			$return["error"] = "not permission";
		}

		return json_encode($return);
	}

}