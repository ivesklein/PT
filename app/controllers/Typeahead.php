<?php

//

class Typeahead extends BaseController
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
				
				$res[] = array('value'=>$value2,'label'=>$name);
			}

		}

		return json_encode($res);
	}

}

?>