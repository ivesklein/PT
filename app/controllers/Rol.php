<?php
class Rol {


	public function funciones(){
		$res = array();
		if(Auth::check()) {

			$id = Auth::user()->id;
			$perms = Permission::whereStaff_id($id)->get();
			$perms->each(function($item){
				array_push($res, $res$item->permission);
			})

			$res = $perms;

		}else{
			$res['error']="login";
		}

		return $res;

	}


}