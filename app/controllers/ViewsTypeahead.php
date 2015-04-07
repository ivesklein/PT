<?php

//

class ViewsTypeahead extends BaseController
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
		return "?";
		//return "hola";
	}

	public function getProfesores()
	{
		
		$res = array();	

		$users = Permission::wherePermission("P")->with(array("staff"=>function($query)
		{
			$term = Input::get('term');	
			return $query->where('name',"LIKE","%".$term."%")->orWhere('surname',"LIKE","%".$term."%");
		}));

		foreach ($users->get() as $value) {

			if($value->staff!=null){

				$name = $value->staff->name." ".$value->staff->surname;
				$value2 = $value->staff->id;
				
				$comisions = 0;

				$comisions += $value->staff->guias()->wherePeriodo(Periodo::active())->count();
				$comisions += $value->staff->comision()->wherePeriodo(Periodo::active())->count();


				$res[] = array('value'=>$value2,'label'=>$name,'comisions'=>$comisions);
			}

		}

		return json_encode($res);
	}

	public function getStaffs()
	{
		
		$res = array();	
		$term = Input::get('term');
		$users = Staff::where('name',"LIKE","%".$term."%")->orWhere('surname',"LIKE","%".$term."%")->get();

		if(!$users->isEmpty()){
			foreach ($users as $user) {

				$name = $user->name." ".$user->surname." ".$user->wc_id;
				$value2 = $user->id;
				
				$comisions = 0;

				$comisions += $user->guias()->wherePeriodo(Periodo::active())->count();
				$comisions += $user->comision()->wherePeriodo(Periodo::active())->count();


				$res[] = array('value'=>$value2,'label'=>$name,'comisions'=>$comisions);
				

			}
		}

		return json_encode($res);
	}

	public function getPeriodos()
	{
		
		$res = array();	
		$term = Input::get('term');
		$pers = Periodo::where('name',"LIKE","%".$term."%")->get();

		if(!$pers->isEmpty()){
			foreach ($pers as $per) {
				$res[] = array('value'=>$per->name);
			}
		}

		return json_encode($res);
	}

}

?>