<?php
class ViewsUsers extends BaseController
{

	public function __construct()
	{
		$this->beforeFilter(function(){
			if(!Auth::check()){
				return View::make('views.expired');
			}
		});
	}

	public function getChangepass()
	{
		return View::make('profile.changepass');
	}
	

	//  LOGIN  //
	public function getLogin()
	{
		return View::make('login.login');
	}
	//  LOGIN  //

	//  USUARIOS  //
	public function getFuncionarios()
	{

		$ahead = array(
			"Nombre",
			"Apellido",
			"Mail", 
			"Cordinador Académico", 
			"Secretario Académico", 
			"Profesor Guía o Comisión", 
			"Profesor Taller", 
			"Ayudante Taller"
		);
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
						"CA"=>array("title"=>"Cordinador Académico", "value"=>"CA","n"=>$id),
						"SA"=>array("title"=>"Secretario Académico", "value"=>"SA","n"=>$id),
						"P"=>array("title"=>"Profesor Guía o Comisión", "value"=>"P","n"=>$id),
						"PT"=>array("title"=>"Profesor Taller", "value"=>"PT","n"=>$id),
						"AY"=>array("title"=>"Ayudante Taller", "value"=>"AY","n"=>$id)
					));

					$mirol = Rol::actual();
					switch ($mirol) {
						case 'CA':
							break;
						case 'SA':
							break;
						case 'PT':
							$array["items"]["SA"]["dis"]=1;
							$array["items"]["CA"]["dis"]=1;
							# code...
							break;
						case 'AY':
							$array["items"]["SA"]["dis"]=1;
							$array["items"]["CA"]["dis"]=1;
							$array["items"]["PT"]["dis"]=1;
							break;
						default:
							$array["items"]["SA"]["dis"]=1;
							$array["items"]["CA"]["dis"]=1;
							$array["items"]["PT"]["dis"]=1;
							$array["items"]["AY"]["dis"]=1;
							$array["items"]["P"]["dis"]=1;
							break;
					}


					$roles = Permission::whereStaff_id($id)->get();
					if(!$roles->isEmpty()){
						foreach ($roles as $role) {
							$array['items'][$role->permission]["sel"]=1;
						}
					}

					$content = View::make("table.cell",array("content"=>$name));
					$content .= View::make("table.cell",array("content"=>$surname));
					$content .= View::make("table.cell",array("content"=>$mail));
					
					foreach ($array['items'] as $rol => $vals) {
						$check = View::make("html.check",$vals);
						$content .= View::make("table.cell",array("content"=>$check));
					}

					$body .= View::make("table.row",array("content"=>$content, "id"=>$id));
				
			}

		}else{
			$message = "No Usuarios";
			$content = View::make("table.cell",array("content"=>$message));
			$body .= View::make("table.row",array("content"=>$content));

		}
		//print_r($res);
		$table = View::make('table.table', array("head"=>$head,"body"=>$body));
		return View::make('views.users.funcionarios', array("table"=>$table));

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






	
}
?>