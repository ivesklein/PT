<?php

//

class ViewsWebcursos extends BaseController
{
	
	public function __construct()
	{
		$this->beforeFilter(function(){
			if(!Auth::check()){
				return View::make('views.expired');
			}
		});
	}




	public function getWebcursos()
	{

		$per = Periodo::active_obj();
        if($per!="false"){
            if(!$per->wc_course==""){///////////

            	$array = array();

            	   
			    function alerta($n){
			    	Log::info($n);
			    	if($n>0){
			    		return ' <span class="badge badge-danger main-badge" style="right: 30px;">'.$n.'</span>';
			    	}else{
			    		return ' <span class="badge badge-danger main-badge" style="right: 30px;"><span class="fa fa-exclamation"></span></span>';
			    	}
			    }


                $l = 0;
                $t = 0;
                $u = 0;
                $todo = 0;

                $l += WCtodo::wherePeriodo($per->name)->whereAction('addlti')->whereDid(0)->count();

                $t += WCtodo::wherePeriodo($per->name)->whereAction('newtarea')->whereDid(0)->count();
                $t += WCtodo::wherePeriodo($per->name)->whereAction('updatetarea')->whereDid(0)->count();
                $t += WCtodo::wherePeriodo($per->name)->whereAction('deletetarea')->whereDid(0)->count();

                $u += WCtodo::wherePeriodo($per->name)->whereAction('newuser')->whereDid(0)->count();
                $u += WCtodo::wherePeriodo($per->name)->whereAction('newgroup')->whereDid(0)->count();
                $u += WCtodo::wherePeriodo($per->name)->whereAction('u2g')->whereDid(0)->count();
                $u += WCtodo::wherePeriodo($per->name)->whereAction('u!2g')->whereDid(0)->count();
                $u += WCtodo::wherePeriodo($per->name)->whereAction('addrol')->whereDid(0)->count();
                $u += WCtodo::wherePeriodo($per->name)->whereAction('delrol')->whereDid(0)->count();

                if($l>0){
                	$array['lti'] = 'No creados'.alerta($l);
                }else{
                	$array['lti'] = 'Ok';
                }

                if($t>0){
                	$array['tareas'] = 'No actualizadas'.alerta($t);
                }else{
                	$array['tareas'] = 'Ok';
                }

                if($u>0){
                	$array['usuarios'] = 'No actualizados'.alerta($u);
                }else{
                	$array['usuarios'] = 'Ok';
                }

                $todo += $l;
                $todo += $t;
                $todo += $u;

                if($todo>0){
                	$array['todo'] = alerta($todo);
                }else{
                	$array['todo'] = 'Ok';
                }

                $array['resumen'] = json_encode(array('lti'=>$l,'tareas'=>$t,'usuarios'=>$u,'todo'=>$todo));
                
                $array['wc_course'] = $per->wc_course;

                return View::make('views.webcursos.webcursos',$array);

        	}else{
	            return View::make('views.webcursos.register');
	        }
	    }else{
	        return "<div class='alert alert-danger'>No hay semestre activo. Coordinación debe activar un semestre para poder continuar.</div>";
	    }
		
	}


}
?>