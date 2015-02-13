<?php
class WCAPI {

	var $lastcontent="";
	var $cookie="";
	var $course=0;
	var $sesskey="";

	function wget($url,$ref_url,$data,$cookie="",$proxy = "null" ,$proxystatus = "false"){

	    try {

	         if($cookie != "") {
	             $fp = fopen($cookie, "w");
	             fclose($fp);
	         }
	         $ch = curl_init();
	         
	         curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	         curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);

	         curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	         curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	         curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	         if ($proxystatus == 'true') {
	             curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
	             curl_setopt($ch, CURLOPT_PROXY, $proxy);
	         }
	         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	         curl_setopt($ch, CURLOPT_URL, $url);
	         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	         curl_setopt($ch, CURLOPT_REFERER, $ref_url);

	         curl_setopt($ch, CURLOPT_HEADER, TRUE);
	         curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	         curl_setopt($ch, CURLOPT_POST, TRUE);
	         $querydata = http_build_query($data);
	         curl_setopt($ch, CURLOPT_POSTFIELDS, $querydata);
	         ob_start();
	         return array("ok"=>curl_exec ($ch)); // execute the curl command
	         ob_end_clean();
	         curl_close ($ch);
	         unset($ch);

	    } catch (Exception $e) {
	        return array("error"=>$e->getMessage());
	    }
	}

	public function __construct(){
		//$this->hash = $this->login();
		$this->ref = "http://webcursos.uai.cl/";
	
	}



	public function login($user, $pass)
	{	
		$return = array();
		$url = "https://webcursos.uai.cl/login/index.php";
		$data = array(
			"username"=>$user,
			"password"=>$pass
		);
		$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
		$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
		
		if(!isset($contenido["error"])){
			$pos = strpos($contenido["ok"], '<a href="http://webcursos.uai.cl/user/profile.php?id=');
			if($pos>0){ //si encuentra entonces
				try {	//
					preg_match_all('|<a href=.http:..webcursos.uai.cl.user.profile.php.id=([0-9]+)" title=".+">(.+)</a>.+<a href="http://webcursos.uai.cl/login/logout.php.sesskey=([0-9a-zA-Z]+)">|', $contenido["ok"], $matches);
					//$return["data"] = $contenido["ok"];
					$this->lastcontent = array("login"=>$contenido["ok"]);
					$this->sesskey = $matches[3][0];

					Session::put('wc.sesskey', $matches[3][0]);
					Session::put('wc.cookie', $this->cookie);
					$per = Periodo::active_obj();
					if($per!=false){
						if(!$per->wc_course==""){
							$this->course = $per->wc_course;
						}else{
							$return['warning'] = "no-course";

							preg_match_all('|<div.+data-courseid="([0-9]+)".+><div class="info"><h3 class="coursename"><a class="" href="http://webcursos.uai.cl/course/view.php.id=[0-9]+">(.+)</a></h3>|U', $contenido["ok"], $matches2);
							$return['courses'] = array("ids"=>$matches2[1],"titles"=>$matches2[2]);

						}
					}

					$return["data"] = array(
						"id"=>$matches[1][0],
						"name"=>$matches[2][0],
						"sesskey"=>$matches[3][0],
						"course"=>$this->course			
					);
				} catch (Exception $e) {
					$return["error"]=$e->getMessage();
				}
			 }else{
				$return["error"]="bad-login";
			 }
		}else{
			$return["error"]=$contenido["error"];
		}
		return $return;
	}

	public function lastSession()
	{	
		$user = Auth::user()->wc_id;
		$this->cookie = "../app/storage/cookies/".$user.".txt";
	}

	public function searchUsers($query)
	{
		$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){
			$url = "http://webcursos.uai.cl/enrol/manual/ajax.php";
			$data = array(
				"id"=>$this->course,  //id curso
				"sesskey"=>$this->sesskey,  //eso mismo
				"action"=>"searchusers",  
				"search"=>$query, //valor a buscar
				"page"=>0,
				"enrolcount"=>0,
				"perpage"=>25,
				"enrolid"=>81020 //matriculacion manual
			);
			$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
			if(isset($contenido["ok"])){
				try {
					$return["data"] = json_decode($contenido["ok"]);
				} catch (Exception $e) {
					$return["error"] = $e->getMessage();
				}
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged";
		}
	}


	
/*
LOGIN
	https://webcursos.uai.cl/login/index.php

	username:dklein@alumnos.uai.cl
	password:****

SEARCH USER
	http://webcursos.uai.cl/enrol/manual/ajax.php

	id:26032  //id curso
	sesskey:odZMdgdN0h  //eso mismo
	action:searchusers  
	search:karol.suchan@uai.cl //valor a buscar
	page:0
	enrolcount:0
	perpage:25
	enrolid:81020 //matriculacion manual

	RESPONSE
	error: ""
	response: {totalusers: 1, users: [{id: "17130",…}]}
		totalusers: 1
		users: [{id: "17130",…}]
			0: {id: "17130",…}
				alternatename: null
				extrafields: "22247684, karol.suchan@uai.cl"
				firstname: "Karol"
				firstnamephonetic: null
				fullname: "Karol Suchan"
				id: "17130"
				imagealt: ""
				lastaccess: "1421180533"
				lastname: "Suchan"
				lastnamephonetic: null
				middlename: null
				picture: "<img src="http://webcursos.uai.cl/pluginfile.php/127288/user/icon/essential/f2?rev=1" alt="Imagen de Karol Suchan" title="Imagen de Karol Suchan" class="userpicture" width="35" height="35" />"
				username: "karol.suchan@uai.cl"
	success: true

ENROLAR USUARIO
	
	http://webcursos.uai.cl/enrol/manual/ajax.php

	id:26032 //id curso
	userid:20446 //userid
	enrolid:81020 //manual
	sesskey:odZMdgdN0h
	action:enrol
	role:5  // {"16":"Coordinador de Curso","5":"Estudiante","4":"Ayudante corrector","14":"Ayudante","3":"Profesor"}
	startdate:3
	duration:0
	recovergrades:0

	RESPONSE
	{"success":true,"response":{},"error":""}

DAR ROL A USUARIO

	http://webcursos.uai.cl/enrol/ajax.php

	id:26032
	action:assign
	sesskey:odZMdgdN0h
	roleid:4
	user:20446

QUITAR ROL A USUARIO

	http://webcursos.uai.cl/enrol/ajax.php

	id:26032
	action:unassign
	sesskey:odZMdgdN0h
	role:5
	user:20446

CREAR GRUPO

	http://webcursos.uai.cl/group/group.php

	id:
	courseid:26032
	sesskey:odZMdgdN0h
	_qf__group_form:1
	mform_isexpanded_id_general:1
	name:G4
	idnumber:21
	description_editor[text]:
	description_editor[format]:1
	description_editor[itemid]:933667216
	enrolmentkey:
	hidepicture:0
	imagefile:47115199
	submitbutton:Guardar cambios

AGREGAR USUARIO A GRUPO

	http://webcursos.uai.cl/group/members.php?group=14402

	sesskey:odZMdgdN0h
	removeselect_searchtext:
	userselector_preserveselected:0
	userselector_autoselectunique:0
	userselector_searchanywhere:0
	add:◄ Agregar
	addselect[]:18565
	addselect_searchtext:

LISTA DE GRUPOS
	
	http://webcursos.uai.cl/group/index.php?id=26032
	html


AGREGAR ENTREGA

	http://webcursos.uai.cl/course/modedit.php

	mform_isexpanded_id_availability:1
	assignsubmission_comments_enabled:1
	assignfeedback_editpdf_enabled:1
	mform_isexpanded_id_submissiontypes:1
	conditiongraderepeats:1
	conditionfieldrepeats:1
	course:26032				//course id
	coursemodule:
	section:2 					//ubicación
	module:31
	modulename:assign
	instance:
	add:assign
	update:0
	return:0
	sr:0
	sesskey:0lmEJNKjdq 			//sesskey
	_qf__mod_assign_mod_form:1
	mform_isexpanded_id_general:1
	mform_isexpanded_id_feedbacktypes:1
	mform_isexpanded_id_submissionsettings:1
	mform_isexpanded_id_groupsubmissionsettings:1
	mform_isexpanded_id_notifications:1
	mform_isexpanded_id_modstandardgrade:1
	mform_isexpanded_id_modstandardelshdr:1
	mform_isexpanded_id_availabilityconditionsheader:1
	name:nombre											//titulo
	introeditor[text]:<p>descripcion</p>				//descipcion en html
	introeditor[format]:1
	introeditor[itemid]:966059896
	allowsubmissionsfromdate[day]:12 					//fecha inicio
	allowsubmissionsfromdate[month]:2 					//fecha inicio
	allowsubmissionsfromdate[year]:2015  				//fecha inicio
	allowsubmissionsfromdate[hour]:0  					//fecha inicio
	allowsubmissionsfromdate[minute]:0  				//fecha inicio
	allowsubmissionsfromdate[enabled]:1 				//fecha inicio
	duedate[day]:19 									//fecha fin
	duedate[month]:2  									//fecha fin
	duedate[year]:2015  								//fecha fin
	duedate[hour]:0 									//fecha fin
	duedate[minute]:0 									//fecha fin
	duedate[enabled]:1 									//fecha fin
	alwaysshowdescription:1
	assignsubmission_onlinetext_enabled:1
	assignsubmission_file_enabled:1
	assignsubmission_file_maxfiles:1
	assignsubmission_file_maxsizebytes:0
	submissiondrafts:0
	requiresubmissionstatement:0
	attemptreopenmethod:none
	teamsubmission:1
	teamsubmissiongroupingid:0
	sendnotifications:1
	grade:0
	advancedgradingmethod_submissions:
	gradecat:20828
	blindmarking:0
	markingworkflow:0
	visible:1
	cmidnumber:
	groupmode:1
	groupingid:0
	conditiongradegroup[0][conditiongradeitemid]:0
	conditiongradegroup[0][conditiongrademin]:
	conditiongradegroup[0][conditiongrademax]:
	conditionfieldgroup[0][conditionfield]:0
	conditionfieldgroup[0][conditionfieldoperator]:contains
	conditionfieldgroup[0][conditionfieldvalue]:
	showavailability:1
	submitbutton:Guardar cambios y mostrar

USER LIST

	http://webcursos.uai.cl/user/index.php?roleid=0&id=26032&search&perpage=5000

			  iiii 																																				  iiiiiiiiiiDDDDDii																																																																																																																																																																												  iDDDDDDDDDDDIDDDDDDDDIIIIII
	<tr class="r1" id="user-index-participants-26032_r3"><td class="cell c0" id="user-index-participants-26032_r3_c0"><input type="checkbox" class="usercheckbox" name="user23817"></td><td class="cell c1" id="user-index-participants-26032_r3_c1"><a href="http://webcursos.uai.cl/user/view.php?id=23817&amp;course=26032"><img src="http://webcursos.uai.cl/pluginfile.php/285528/user/icon/essential/f2?rev=3339516" alt="Imagen de Pablo Jose Carrasco Melo" title="Imagen de Pablo Jose Carrasco Melo" class="userpicture" width="35" height="35"></a></td><td class="cell c2" id="user-index-participants-26032_r3_c2"><strong><a href="http://webcursos.uai.cl/user/view.php?id=23817&amp;course=26032">Pablo Jose Carrasco Melo</a></strong></td><td class="cell c3" id="user-index-participants-26032_r3_c3">18018721</td><td class="cell c4" id="user-index-participants-26032_r3_c4">pabcarrasco@alumnos.uai.cl</td><td class="cell c5" id="user-index-participants-26032_r3_c5">Santiago</td><td class="cell c6" id="user-index-participants-26032_r3_c6">Chile</td><td class="cell c7" id="user-index-participants-26032_r3_c7">2 días 23 horas</td></tr>

	<tr class="r.".+name="user([0-9]+)">.+>(.+@.*uai.cl)<

*/






}

?>