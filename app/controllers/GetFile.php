<?php
class GetFile  extends BaseController
{

public static function test()
{
    return true;
}

public function feedback($id)
{
	$nota = Nota::find($id);
	if(!empty($nota)){
		$ok=false;

		//si tiene permiso
		$tema = Subject::find($nota->subject_id);
		if(!empty($tema)){
			$user = Session::get('wc.user' ,"0");
			if($user!="0"){
				//alumno1
				if($user==$tema->student1){
					$ok=true;
				}
				//alumno2
				if($user==$tema->student2){
					$ok=true;	
				}
			}

			if(Auth::check()){
				//profesor
				if(Auth::user()->wc_id==$tema->adviser){
					$ok=true;
				}

				//ptaller secre cordinacion
				$rol = Rol::actual();
				if($rol=="PT" || $rol=="SA" || $rol=="CA"){
					$ok=true;
				}
			}
		}
		
		if($ok==true){
			$file = $nota->file;
			if(!empty($file)){
				$filename = $nota->filename;

				//$file= $file;
		        $headers = array(
				  'Content-Type' => $nota->filetype,
				);
		        return Response::download($file, $nota->filename, $headers);

			}else{
				App::abort(404);
			}
		}else{
			App::abort(404);
		}
	}else{
		App::abort(404);
	}

	//return $id;
}



}
?>