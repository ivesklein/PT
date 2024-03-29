<?php
class WCAPI {

	var $lastcontent=array();
	var $cookie="";
	var $course=0;
	var $sesskey="";
	var $count=0;
	var $enrolid=-1;
	var $times=array();

	public static function test()
    {
        return true;
    }	

	function wget($url,$ref_url,$data,$cookie="",$proxy = "null" ,$proxystatus = "false"){

		$time_start = microtime(true);

	    try {

	    		//$cookie = "cookie.coo";
	         

	         if($cookie != "") {
	          	if(!file_exists($cookie)){
	             	$fp = fopen($cookie, "w");
	             	fclose($fp);
	         	}
	         }
	         $ch = curl_init();
	         
	         curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
	         curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);

	         //curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);

	         curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)");
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

	         curl_setopt($ch, CURLOPT_HEADER, FALSE);
	         curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);


	         if(count($data)>0){
		        curl_setopt($ch, CURLOPT_POST, TRUE);
		        $querydata = http_build_query($data);
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $querydata);
	         }else{
	         	curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
	         }

	         
	         $res = curl_exec($ch);// execute the curl command
	         curl_close ($ch);
	         unset($ch);

	         $time_end = microtime(true);
			 $time = $time_end - $time_start;
			 $this->times[] = array("url"=>$url, "time"=>$time);
	         return array("ok"=>$res,'cookie'=>$cookie); 



	    } catch (Exception $e) {
	        return array("error"=>$e->getMessage());
	    }
	}

	public function __construct(){
		//$this->hash = $this->login();
		$this->ref = "http://webcursos.uai.cl";
	
	}

	public function getTimes()
	{
		return $this->times;
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
		
		$fp = fopen($this->cookie, "w");
	    fclose($fp);

		$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
		
		if(!isset($contenido["error"])){
			$pos = strpos($contenido["ok"], '<a href="http://webcursos.uai.cl/user/profile.php?id=');
			if($pos>0){ //si encuentra entonces
				try {	//
					preg_match_all('|<a href=.http:..webcursos.uai.cl.user.profile.php.id=([0-9]+)" title=".+">(.+)</a>.+<a href="http://webcursos.uai.cl/login/logout.php.sesskey=([0-9a-zA-Z]+)">|', $contenido["ok"], $matches);
					//$return["data"] = $contenido["ok"];
					$this->lastcontent['login'] = $contenido["ok"];
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
			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";

			Log::info("WCAPI find:".$query);

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
		return $return;
	}

	public function userList()
	{
		$return = array("users"=>array());
		if($this->cookie!="" && $this->course!=0){
			$url = "http://webcursos.uai.cl/enrol/users.php?id=".$this->course;//&perpage=5000";
			$data = array();
			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			if(isset($this->lastcontent['userlist'])){
				$contenido['ok'] = $this->lastcontent['userlist'];
			}else{				
				$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
				if(isset($contenido["ok"])){
					$this->lastcontent['userlist'] = $contenido["ok"];
				}
			}
			if(isset($contenido["ok"])){
				try {

					preg_match_all('|<tr class="userinforow.+id="user_([0-9]+)">.+class="subfield subfield.email">(.+)<.div><.td.+<div class="roles">(.*)<.div><.td>.+<div class="groups">(.*)<.div>.+<.td>|Uus', $contenido["ok"], $matches);
					//.+<div class="subfield subfield_email">(.+)<.div></td.+<div class="roles">(.+<.div>)<.div>.+<div class="groups">(.+<.div>)<.div><div class="addgroup">
					//preg_match_all('|<tr class="userinforow.+" id="user_([0-9]+)">.+<div class="subfield subfield_email">(.+)<.div></td.+<div class="roles">(.+<.div>)<.div>.+<div class="groups">(.+<.div>)<.div><div class="addgroup">|U', $contenido["ok"], $matches);
					foreach ($matches[2] as $key => $user) {
						
						$roles = array();
						preg_match_all('|<div class="role.+">(.+)<.+rel="([0-9]+)"|Uus', $matches[3][$key], $rolesmatch);
						foreach ($rolesmatch[2] as $key2 => $idrol) {
							$roles[$idrol] = $rolesmatch[1][$key2];
						}

						$grupos = array();
						preg_match_all('|<div class="group" rel="([0-9]+)">(.+)<|Uus', $matches[4][$key], $groupsmatch);
						foreach ($groupsmatch[2] as $key3 => $idgroup) {
							$grupos[html_entity_decode($idgroup)] = $groupsmatch[1][$key3];
						}

						$return["users"][strtolower($user)] = array("uid"=>$matches[1][$key], "roles"=>$roles, "grupos"=>$grupos);
					}

					//print_r($matches);
					//$return["url"] = $url;
					//$return["ok"] = $matches;
					Log::info("WCAPI userlist");
					Log::info("WCAPI ".json_encode($return["users"]));


				} catch (Exception $e) {
					$return["error"] = $e->getMessage();
				}
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged";
		}

		return $return;
	}

	public function groupList()
	{
		$return = array("groups"=>array());
		if($this->cookie!="" && $this->course!=0){
			$url = "http://webcursos.uai.cl/enrol/users.php?id=".$this->course;//&perpage=5000";
			$data = array();
			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			if(isset($this->lastcontent['userlist'])){
				$contenido['ok'] = $this->lastcontent['userlist'];
			}else{
				$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
				if(isset($contenido["ok"])){
					$this->lastcontent['userlist'] = $contenido["ok"];
				}
			}
			
			if(isset($contenido["ok"])){
				try {

					preg_match_all('|<select name="filtergroup" id="id_filtergroup">(.+)<.select>|Uus', $contenido["ok"], $matches);
					//.+<div class="subfield subfield_email">(.+)<.div></td.+<div class="roles">(.+<.div>)<.div>.+<div class="groups">(.+<.div>)<.div><div class="addgroup">
					//preg_match_all('|<tr class="userinforow.+" id="user_([0-9]+)">.+<div class="subfield subfield_email">(.+)<.div></td.+<div class="roles">(.+<.div>)<.div>.+<div class="groups">(.+<.div>)<.div><div class="addgroup">|U', $contenido["ok"], $matches);

					$grupos = array();
					if(isset($matches[1][0])){
						preg_match_all('|<option value="([0-9]+)">(.+)<.option>|Uus', $matches[1][0], $groupsmatch);
						foreach ($groupsmatch[2] as $key3 => $idgroup) {
							if($groupsmatch[1][$key3]!=0){//sd & ger = 1234
								$return["groups"][html_entity_decode($idgroup)] = $groupsmatch[1][$key3];
							}
						}
					}

				} catch (Exception $e) {
					$return["error"] = $e->getMessage();
				}
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged";
		}

		return $return;
	}
	
	public function createGroup($name,$id)
	{
		$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){
			$url = "http://webcursos.uai.cl/group/group.php";//&perpage=5000";
			$data = array(	
				"id"=>"",
				"courseid"=>$this->course,
				"sesskey"=>$this->sesskey,
				"_qf__group_form"=>"1",
				"mform_isexpanded_id_general"=>"1",
				"name"=>$name,
				"idnumber"=>$id,
				//"description_editor[text]"=>"",
				//"description_editor[format]"=>"1",
				//"description_editor[itemid]"=>"933667216",
				"enrolmentkey"=>"",
				"hidepicture"=>"0",
				"imagefile"=>"47115199",
				"submitbutton"=>"Guardar cambios"
			);
			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			$contenido = $this->wget($url , $this->ref , $data , $this->cookie);

			
			if(isset($contenido["ok"])){
				try {

					preg_match_all('|<option value="([0-9]+)" selected.+title=".+">(.+) .[0-9]+.<.option>|Uus', $contenido["ok"], $matches);

					if(isset($matches[0][0])){
						$return["ok"] = $matches[1][0];
					}else{
						$return["error"] = "Error, posiblemente el grupo ya existe";
					}

				} catch (Exception $e) {
					$return["error"] = $e->getMessage();
				}
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged:".$this->sesskey."-".$this->course;
		}

		return $return;
	}

	public function searchUser($query)
	{
		$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){
			if($this->enrolid==-1){

				$url = "http://webcursos.uai.cl/enrol/users.php?id=".$this->course;//&perpage=5000";
				$data = array();
				//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
				$contenido = $this->wget($url , $this->ref , $data , $this->cookie);				
				if(isset($contenido["ok"])){
					try {

						preg_match_all('|<input type="hidden" name="enrolid" value="([0-9]+)" .>|Uus', $contenido["ok"], $matches);

						if(isset($matches[0][0])){
							$this->enrolid = $matches[1][0];
							Log::info("WCAPI pre1:".$query);
							return $this->searchUserOk($query);
						}else{
							$return["error"] = "Error, enrolid no encontrado";
						}
					} catch (Exception $e) {
						$return["error"] = $e->getMessage();
					}
				}
			}else{
				Log::info("WCAPI pre0:".$query);
				return $this->searchUserOk($query);
			}
		}
		return $return;

	}

	public function searchUserOk($query)
	{
		$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){
			$url = "http://webcursos.uai.cl/enrol/manual/ajax.php";//&perpage=5000";
			$data = array(	
				"id"=>$this->course,  //id curso
				"sesskey"=>$this->sesskey,  //eso mismo
				"action"=>"searchusers",  
				"search"=>$query, //valor a buscar
				"page"=>"0",
				"enrolcount"=>"0",
				"perpage"=>"25",
				"enrolid"=>$this->enrolid //matriculacion manual
			);
			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
				
			Log::info("WCAPI find:".$query);

			if(isset($contenido["ok"])){
				try {

					$res = json_decode($contenido["ok"]);
					if($res->success==1){
						if($res->response->totalusers>0){
							if($res->response->users[0]->username==$query){
								$return["ok"]=$res->response->users[0];
							}else{
								$return["warning"]="empty, no match";
							}
						}else{
							$return["warning"]="empty, no results:q:".$query.":res:".$contenido["ok"];
						}
					}else{
						$return["error"]="error respuesta";
					}

					//$return["ok"]=$res;

				} catch (Exception $e) {
					$return["error"] = $e->getMessage();
				}
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged:".$this->sesskey."-".$this->course;
		}

		return $return;
	}

	public function enrolUser($uid,$rol)
	{
		$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){
			if($this->enrolid==-1){

				$url = "http://webcursos.uai.cl/enrol/users.php?id=".$this->course;//&perpage=5000";
				$data = array();
				//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
				$contenido = $this->wget($url , $this->ref , $data , $this->cookie);				
				if(isset($contenido["ok"])){
					try {

						preg_match_all('|<input type="hidden" name="enrolid" value="([0-9]+)" .>|Uus', $contenido["ok"], $matches);

						if(isset($matches[0][0])){
							$this->enrolid = $matches[1][0];
							Log::info("WCAPI pre1:".$uid);
							return $this->enrolUserOk($uid,$rol);
						}else{
							$return["error"] = "Error, enrolid no encontrado";
						}
					} catch (Exception $e) {
						$return["error"] = $e->getMessage();
					}
				}
			}else{
				Log::info("WCAPI pre0:".$uid);
				return $this->enrolUserOk($uid,$rol);
			}
		}
		return $return;

	}

	public function enrolUserOk($uid,$rol)
	{
		$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){

			$url = "http://webcursos.uai.cl/enrol/manual/ajax.php";//&perpage=5000";
			$data = array(	
				"id"=>$this->course, //id curso
				"userid"=>$uid, //userid
				"enrolid"=>$this->enrolid, //manual
				"sesskey"=>$this->sesskey,
				"action"=>"enrol",
				"role"=>$rol,  // 
				"startdate"=>"3",
				"duration"=>"0",
				"recovergrades"=>"0"
			);
			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
			
			Log::info("WCAPI registrar:".$uid);

			if(isset($contenido["ok"])){
				try {

					$res = json_decode($contenido["ok"]);
					
					Log::info("WCAPI registrar:".$contenido["ok"]);

					if($res->success==1){
						$return["ok"]=1;
					}else{
						$return["error"]="error respuesta";
					}

					//$return["ok"]=$res;

				} catch (Exception $e) {
					$return["error"] = $e->getMessage();
				}
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged:".$this->sesskey."-".$this->course;
		}

		return $return;
	}

	public function user2group($uid,$group)
	{
		$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){
			http://webcursos.uai.cl/enrol/users.php
			$url = "http://webcursos.uai.cl/enrol/users.php";//&perpage=5000";
			//$url = "http://webcursos.uai.cl/group/members.php?group=".$group;//&perpage=5000";
			
			$data = array(	
				"id"=>$this->course,
				"user"=>$uid,
				"action"=>"addmember",
				"ifilter"=>"",
				"page"=>"0",
				"perpage"=>"100",
				"sort"=>"lastname",
				"dir"=>"ASC",
				"sesskey"=>$this->sesskey,
				"_qf__enrol_users_addmember_form"=>"1",
				"mform_isexpanded_id_general"=>"1",
				"groupid"=>$group,
				"submitbutton"=>"Guardar cambios",
			);


			/*$data = array(	
				"sesskey"=>$this->sesskey,
				"removeselect_searchtext"=>"",
				"userselector_preserveselected"=>"0",
				"userselector_autoselectunique"=>"0",
				"userselector_searchanywhere"=>"0",
				"add"=>"◄ Agregar",
				"addselect[]"=>$uid,
				"addselect_searchtext"=>""

			);*/
			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
			
			Log::info("WCAPI u2g:".$uid);

			if(isset($contenido["ok"])){
				try {

					/*$res = json_decode($contenido["ok"]);
					if($res->success==1){
						$return["ok"]=1;
					}else{
						$return["error"]="error respuesta";
					}*/

					$return["ok"]=array("data"=>$data,"res"=>$contenido["ok"]);

				} catch (Exception $e) {
					$return["error"] = $e->getMessage();
				}
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged:".$this->sesskey."-".$this->course;
		}

		return $return;
	}

	public function usernot2group($uid,$group)
	{
		$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){
			$url = "http://webcursos.uai.cl/group/members.php?group=".$group;//&perpage=5000";
			$data = array(	
				"sesskey"=>$this->sesskey,
				"removeselect_searchtext"=>"",
				"userselector_preserveselected"=>"0",
				"userselector_autoselectunique"=>"0",
				"userselector_searchanywhere"=>"0",
				"remove"=>"Quitar ►",
				"removeselect[]"=>$uid,
				"addselect_searchtext"=>""

			);
			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
			
			if(isset($contenido["ok"])){
				try {

					$return["ok"]=array("data"=>$data,"res"=>$contenido["ok"]);

				} catch (Exception $e) {
					$return["error"] = $e->getMessage();
				}
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged:".$this->sesskey."-".$this->course;
		}

		return $return;
	}

	public function role2user($uid,$rol)
	{
		$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){
			$url = "http://webcursos.uai.cl/enrol/ajax.php";//&perpage=5000";
			$data = array(	
				"id"=>$this->course,
				"action"=>"assign",
				"sesskey"=>$this->sesskey,
				"roleid"=>$rol,
				"user"=>$uid
			);
			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
			
			if(isset($contenido["ok"])){
				try {

					$res = json_decode($contenido["ok"]);
					if($res->success==1){
						$return["ok"]=1;
					}else{
						$return["error"]="error respuesta";
					}

				} catch (Exception $e) {
					$return["error"] = $e->getMessage();
				}
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged:".$this->sesskey."-".$this->course;
		}

		return $return;
	}

	public function rolenot2user($uid,$rol)
	{
		$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){
			$url = "http://webcursos.uai.cl/enrol/ajax.php";//&perpage=5000";
			$data = array(	
				"id"=>$this->course,
				"action"=>"unassign",
				"sesskey"=>$this->sesskey,
				"roleid"=>$rol,
				"user"=>$uid
			);
			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
			
			if(isset($contenido["ok"])){
				try {

					$res = json_decode($contenido["ok"]);
					if($res->success==1){
						$return["ok"]=1;
					}else{
						$return["error"]="error respuesta";
					}

				} catch (Exception $e) {
					$return["error"] = $e->getMessage();
				}
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged:".$this->sesskey."-".$this->course;
		}

		return $return;
	}

	public function createTarea($title,$date, $uptime, $idupdate = 0)
	{
		$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){
			$url = "http://webcursos.uai.cl/course/modedit.php";//&perpage=5000";

			$datestart = $date->copy()->subDays($uptime);

			$startday = $datestart->day;
			$startmonth = $datestart->month;
			$startyear = $datestart->year;
			$endday = $date->day;
			$endmonth = $date->month;
			$endyear = $date->year;

			$startstamp = ""; /////////////////////////////////


			$data = array(	

				"mform_isexpanded_id_availability"=>"1",
				"assignsubmission_comments_enabled"=>"1",
				"assignfeedback_editpdf_enabled"=>"1",
				"mform_isexpanded_id_submissiontypes"=>"1",


				"course"=>$this->course,
				"coursemodule"=>"",
				"section"=>"2", 					//ubicación
				"module"=>"31",
				"modulename"=>"assign",
				"instance"=>"",
				"add"=>"assign",
				"update"=>"0",
				"return"=>"0",
				"sr"=>"0",
				"sesskey"=>$this->sesskey, 			//sesskey
				"_qf__mod_assign_mod_form"=>"1",
				"mform_isexpanded_id_general"=>"1",
				"mform_isexpanded_id_feedbacktypes"=>"1",
				"mform_isexpanded_id_submissionsettings"=>"1",
				"mform_isexpanded_id_groupsubmissionsettings"=>"1",
				"mform_isexpanded_id_notifications"=>"1",
				"mform_isexpanded_id_modstandardgrade"=>"1",
				"mform_isexpanded_id_modstandardelshdr"=>"1",
				"mform_isexpanded_id_availabilityconditionsheader"=>"1",
				"name"=>$title,											//titulo
				"introeditor[text]"=>"<p>Entregar antes de ".$date->format('m/d/Y')." a las 23:55</p>",				//descipcion en html
				"introeditor[format]"=>"1",
				"introeditor[itemid]"=>"966059896",

				"allowsubmissionsfromdate[day]"=>$startday, 					//fecha inicio
				"allowsubmissionsfromdate[month]"=>$startmonth, 					//fecha inicio
				"allowsubmissionsfromdate[year]"=>$startyear,  				//fecha inicio
				"allowsubmissionsfromdate[hour]"=>"0",  					//fecha inicio
				"allowsubmissionsfromdate[minute]"=>"0",  				//fecha inicio
				"allowsubmissionsfromdate[enabled]"=>"1", 				//fecha inicio
				"duedate[day]"=>$endday, 									//fecha fin
				"duedate[month]"=>$endmonth,  									//fecha fin
				"duedate[year]"=>$endyear,  								//fecha fin
				"duedate[hour]"=>"23", 									//fecha fin
				"duedate[minute]"=>"55", 									//fecha fin
				"duedate[enabled]"=>"1", 									//fecha fin
				"alwaysshowdescription"=>"1",
				"assignsubmission_onlinetext_enabled"=>"1",
				"assignsubmission_file_enabled"=>"1",
				"assignsubmission_file_maxfiles"=>"1",
				"assignsubmission_file_maxsizebytes"=>"0",
				"submissiondrafts"=>"0",
				"requiresubmissionstatement"=>"0",
				"attemptreopenmethod"=>"none",
				"teamsubmission"=>"1",
				"teamsubmissiongroupingid"=>"0",
				"sendnotifications"=>"1", //notificacion a ayudante
				"sendstudentnotifications"=>1,
				"grade[modgrade_type]"=>"point",
				"grade[modgrade_point]"=>100,
				"advancedgradingmethod_submissions"=>"",
				"gradecat"=>"20828",
				"blindmarking"=>"0",
				"markingworkflow"=>"0",
				"visible"=>"1",
				"cmidnumber"=>"",
				"groupmode"=>"1",
				"availabilityconditionsjson"=>'{"op":"&","c":[],"showc":[]}',
				//"availabilityconditionsjson"=>'{"op":"&","c":[{"type":"date","d":">=","t":'.$startstamp.'}],"showc":[false]}',
				"submitbutton"=>"Guardar cambios y mostrar",

			);
			
			if($idupdate!=0){
				$data["coursemodule"] = $idupdate;
				$data["update"]  = $idupdate;
				$data["add"] = 0;
				$data["groupingid"] = 0;

				$resi = $this->getInstance($idupdate);
				if(isset($resi['ok'])){
					$data["instance"] = $resi['ok']['instance'];
					$data["introeditor[itemid]"] = $resi['ok']['introeditor'];
					$data["introattachments"] = $resi['ok']['introattachments'];
				}else{
					return array('error'=>$resi);
				}

			}

			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
			
			if(isset($contenido["ok"])){
				try {

					if($idupdate!=0){

						//preg_match_all('|<a title=".+" href="http:..webcursos.uai.cl.mod.assign.view.php.id=([0-9]+])">.+<.a>|', $contenido["ok"], $matches);
						preg_match_all('|cmid-([0-9]+)|', $contenido["ok"], $matches);
						if(isset($matches[0][0])){
							if($matches[1][0]==$idupdate){
								$return["ok"] = $matches[1][0];
							}else{
								$return['error'] = array('post'=>$data,'res'=>$contenido["ok"]);
							}
						}else{
							$return["error"] = array("no Matches"=>$data,'res'=>$contenido["ok"]);
						}

					}else{
												
						preg_match_all('|cmid-([0-9]+)|', $contenido["ok"], $matches);

						if(isset($matches[0][0])){
							$return["ok"] = $matches[1][0];
						}else{
							$return["error"] = array("no Matches"=>$data,'res'=>$contenido["ok"]);
						}
					}



				} catch (Exception $e) {
					$return["error"] = $e->getMessage();
				}
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged:".$this->sesskey."-".$this->course;
		}

		return $return;
	}

	public function createLTI($title,$urllti,$icon="")
	{
		$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){
			$url = "http://webcursos.uai.cl/course/modedit.php";//&perpage=5000";
			
			$key = "webcursos";
			$con = Consumer::whereKey($key)->get();
			if(!$con->isEmpty()){
				$secret = $con->first()->secret;
			}else{
				$secret = "";
			}

			$data = array(	
				"urlmatchedtypeid"=>"undefined",
				"conditiongraderepeats"=>"1",
				"conditionfieldrepeats"=>"1",
				"course"=>$this->course,
				"coursemodule"=>"",
				"section"=>"1",
				"module"=>"28",
				"modulename"=>"lti",
				"instance"=>"",
				"add"=>"lti",
				"update"=>"0",
				"return"=>"0",
				"sr"=>"0",
				"sesskey"=>$this->sesskey,
				"_qf__mod_lti_mod_form"=>"1",
				"mform_showmore_id_general"=>"1",
				"mform_showmore_id_modstandardelshdr"=>"0",
				"mform_isexpanded_id_general"=>"1",
				"mform_isexpanded_id_privacy"=>"0",
				"mform_isexpanded_id_modstandardelshdr"=>"0",
				"mform_isexpanded_id_availabilityconditionsheader"=>"0",
				"name"=>$title,
				"introeditor[text]"=>"",
				"introeditor[format]"=>"1",
				"introeditor[itemid]"=>"781240441",
				"showtitlelaunch"=>"0",
				"typeid"=>"0",
				"toolurl"=>$urllti,
				"securetoolurl"=>"",
				"launchcontainer"=>"2",
				"resourcekey"=>$key,
				"password"=>$secret,
				"instructorcustomparameters"=>"",
				"icon"=>$icon,
				"secureicon"=>"",
				"instructorchoicesendname"=>"1",
				"instructorchoicesendemailaddr"=>"1",
				"instructorchoiceacceptgrades"=>"0",
				"visible"=>"1",
				"cmidnumber"=>"",
				/*"conditiongradegroup[0][conditiongradeitemid]"=>"0",
				"conditiongradegroup[0][conditiongrademin]"=>"",
				"conditiongradegroup[0][conditiongrademax]"=>"",
				"conditionfieldgroup[0][conditionfield]"=>"0",
				"conditionfieldgroup[0][conditionfieldoperator]"=>"contains",
				"conditionfieldgroup[0][conditionfieldvalue]"=>"",
				*/"showavailability"=>"1",
				"availabilityconditionsjson"=>'{"op":"&","c":[],"showc":[]}',
				"submitbutton"=>"Guardar cambios y mostrar"
			);
			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
			
			if(isset($contenido["ok"])){
				try {

					preg_match_all('|cmid-([0-9]+)|', $contenido["ok"], $matches);

					if(isset($matches[0][0])){
						$return["ok"] = $matches[1][0];
					}else{
						$return["error"] = array("no Matches"=>$data);
					}

				} catch (Exception $e) {
					$return["error"] = $e->getMessage();
				}
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged:".$this->sesskey."-".$this->course;
		}

		return $return;
	}

	public function deleteResource($id)
	{
	$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){
			$url = "http://webcursos.uai.cl/course/rest.php";//&perpage=5000";
			$data = array(	
				"class"=>"resource",
				"action"=>"DELETE",
				"id"=>$id,
				"sesskey"=>$this->sesskey,
				"courseId"=>$this->course
			);
			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			$contenido = $this->wget($url , $this->ref , $data , $this->cookie);

			
			if(isset($contenido["ok"])){
				$return["ok"] = 1;
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged:".$this->sesskey."-".$this->course;
		}

		return $return;
	}

	public function getInstance($idrecurso)
	{
	$return = array();
		if($this->cookie!="" && $this->course!=0 && $this->sesskey!=""){
			$url = "http://webcursos.uai.cl/course/modedit.php?update=".$idrecurso."&return=0&sr=0";//&perpage=5000";
			$data = array();
			//$this->cookie = "../app/storage/cookies/".$user.".txt";//Staff::whereWc_id($user)->first()->id.".txt";
			$contenido = $this->wget($url , $this->ref , $data , $this->cookie);
			
			if(isset($contenido["ok"])){
				try {

					preg_match_all('|<input name="instance" type="hidden" value="([0-9]+)" .>|', $contenido["ok"], $matches);
					preg_match_all('|<input value="([0-9]+)" name="introattachments" type="hidden" .>|', $contenido["ok"], $matches2);
					preg_match_all('|<input type="hidden" name="introeditor.itemid." value="([0-9]+)" .>|', $contenido["ok"], $matches3);


					if(isset($matches[0][0])){
						$return["ok"] = array();
						$return["ok"]['instance'] = $matches[1][0];
					}else{
						$return["error"] = array("no Matches"=>$contenido["ok"]);
					}

					if(isset($matches2[0][0])){
						$return["ok"]['introattachments'] = $matches2[1][0];
					}else{
						$return["error"] = array("no Matches"=>$contenido["ok"]);
					}

					if(isset($matches3[0][0])){
						$return["ok"]['introeditor'] = $matches3[1][0];
					}else{
						$return["error"] = array("no Matches"=>$contenido["ok"]);
					}
					

				} catch (Exception $e) {
					$return["error"] = $e->getMessage();
				}
			}else{
				$return["error"] = $contenido["error"];
			}
		}else{
			$return["error"]="not-logged:".$this->sesskey."-".$this->course;
		}

		return $return;
	}


/*

GET ENROL METHOD

http://webcursos.uai.cl/enrol/users.php?id=28371
<input type="hidden" name="enrolid" value="91330" />


GET INSTANCE
	http://webcursos.uai.cl/course/modedit.php?update=624972&return=0&sr=0

	<input name="instance" type="hidden" value="17490" />


DELETE RESOURCE
	http://webcursos.uai.cl/course/rest.php

	class:resource
	action:DELETE
	id:624919
	sesskey:1a08LSWzTy
	courseId:26032

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

	


	http://webcursos.uai.cl/enrol/users.php?id=26032

	iiiiiiiiiiiiiiiiii	   iiiiiiiiiDDDDDii
<tr class="userinforow r0" id="user_19736">
																																																																																																																																					 					  iiiiiiiiiiiiiiiiDDDDDDdDDDDDDDDddddddiiiiii
<td class="field col_userdetails cell c0" style=""><div class="subfield subfield_picture"><a href="http://webcursos.uai.cl/user/view.php?id=19736&amp;course=26032"><img src="http://webcursos.uai.cl/pluginfile.php/186496/user/icon/essential/f2?rev=2329553" alt="Imagen de Isabel Angelica Oryan Galvez" title="Imagen de Isabel Angelica Oryan Galvez" class="userpicture" width="35" height="35"></a></div> <div class="subfield subfield_firstname">Isabel Angelica Oryan Galvez</div> <div class="subfield subfield_idnumber">17698441</div> <div class="subfield subfield_email">ioryan@alumnos.uai.cl</div></td>

<td class="field col_lastseen cell c1" style="">2 días</td>

<td class="field col_role cell c2" style=""><div class="addrole"><a class="assignrolelink" title="Asignar roles" href="http://webcursos.uai.cl/enrol/users.php?id=26032&amp;page=0&amp;perpage=100&amp;sort=lastname&amp;dir=ASC&amp;action=assign&amp;user=19736"><img alt="Asignar roles" src="http://webcursos.uai.cl/theme/image.php/essential/core/1421344949/t/enroladd"></a></div>

iiiiiiiiiiiiiiiiiii
<div class="roles">
	<div class="role role_14">Ayudante
		<a class="unassignrolelink" rel="14" title="Desasignar rol Ayudante" href="http://webcursos.uai.cl/enrol/users.php?id=26032&amp;page=0&amp;perpage=100&amp;sort=lastname&amp;dir=ASC&amp;action=unassign&amp;roleid=14&amp;user=19736">
			<img alt="Desasignar rol Ayudante" src="http://webcursos.uai.cl/theme/image.php/essential/core/1421344949/t/delete">
		</a>
	iiiiii
	</div>
iiiiii
</div>
</td>

<td class="field col_group cell c3" style="">
iiiiiiiiiiiiiiiiiiii
<div class="groups">
	<div class="group" rel="14399">G1
		<a href="http://webcursos.uai.cl/enrol/users.php?id=26032&amp;page=0&amp;perpage=100&amp;sort=lastname&amp;dir=ASC&amp;action=removemember&amp;group=14399&amp;user=19736">
			<img alt="Eliminar al usuario del grupo G1" src="http://webcursos.uai.cl/theme/image.php/essential/core/1421344949/t/delete">
		</a>
	</div>
	<div class="group" rel="14400">G2
		<a href="http://webcursos.uai.cl/enrol/users.php?id=26032&amp;page=0&amp;perpage=100&amp;sort=lastname&amp;dir=ASC&amp;action=removemember&amp;group=14400&amp;user=19736">
			<img alt="Eliminar al usuario del grupo G2" src="http://webcursos.uai.cl/theme/image.php/essential/core/1421344949/t/delete">
		</a>
	iiiiii	
	</div>
iiiiii
</div>
iiiiiiiiiiiiiiiiiiiiii
<div class="addgroup"><a href="http://webcursos.uai.cl/enrol/users.php?id=26032&amp;page=0&amp;perpage=100&amp;sort=lastname&amp;dir=ASC&amp;action=addmember&amp;user=19736"><img alt="Agregar usuarios al grupo" src="http://webcursos.uai.cl/theme/image.php/essential/core/1421344949/t/enroladd"></a></div></td>

<td class="field col_enrol cell c4 lastcol" style=""><div class="enrolment"><span>Matriculacion manual desde lunes, 2 de febrero de 2015, 00:00</span><a class="unenrollink" rel="1596610" title="Dar de baja" href="http://webcursos.uai.cl/enrol/unenroluser.php?id=26032&amp;page=0&amp;perpage=100&amp;sort=lastname&amp;dir=ASC&amp;ue=1596610"><img alt="" class="smallicon" title="" src="http://webcursos.uai.cl/theme/image.php/essential/core/1421344949/t/delete"></a><a class="editenrollink" rel="1596610" title="Editar" href="http://webcursos.uai.cl/enrol/editenrolment.php?id=26032&amp;page=0&amp;perpage=100&amp;sort=lastname&amp;dir=ASC&amp;ue=1596610"><img alt="" class="smallicon" title="" src="http://webcursos.uai.cl/theme/image.php/essential/core/1421344949/t/edit"></a></div></td>

</tr>


<tr class="userinforow.+" id="user_([0-9]+)">.+<div class="subfield subfield_email">(.+)<.div></td.+<div class="roles">(.+<.div>)<.div>.+<div class="groups">(.+<.div>)<.div><div class="addgroup">


		 iiiiiiiiiii         iDDDDDDDD
	<div class="role role_14">Ayudante
		i 		  					iiiiiDDi
		<a class="unassignrolelink" rel="14" title="Desasignar rol Ayudante" href="http://webcursos.uai.cl/enrol/users.php?id=26032&amp;page=0&amp;perpage=100&amp;sort=lastname&amp;dir=ASC&amp;action=unassign&amp;roleid=14&amp;user=19736">
			<img alt="Desasignar rol Ayudante" src="http://webcursos.uai.cl/theme/image.php/essential/core/1421344949/t/delete">
		</a>
	</div>
	
	<div class="role.+">(.+)<.+rel="([0-9]+)"

		 iiiiiiiiiiiii iiiiiDDDDDiiDD
	<div class="group" rel="14399">G1
		i
		<a href="http://webcursos.uai.cl/enrol/users.php?id=26032&amp;page=0&amp;perpage=100&amp;sort=lastname&amp;dir=ASC&amp;action=removemember&amp;group=14399&amp;user=19736">
			<img alt="Eliminar al usuario del grupo G1" src="http://webcursos.uai.cl/theme/image.php/essential/core/1421344949/t/delete">
		</a>
	</div>

	<div class="group" rel="([0-9]+)">(.+)<



<select name="filtergroup" id="id_filtergroup">
	<option value="0">Todos los participantes</option>
	<option value="14399">G1</option>
	<option value="14400">G2</option>
	<option value="14401">G3</option>
	<option value="14402">G4</option>
	<option value="14403">G5</option>
	<option value="14404">G7</option>
</select>

<select name="filtergroup" id="id_filtergroup">(.+)<.select>
<option value="([0-9]+)">(.+)<.option>




CREATE LTI

	http://webcursos.uai.cl/course/modedit.php

	urlmatchedtypeid:undefined
	conditiongraderepeats:1
	conditionfieldrepeats:1
	course:26032
	coursemodule:
	section:1
	module:28
	modulename:lti
	instance:
	add:lti
	update:0
	return:0
	sr:0
	sesskey:Zf1dkYMDUn
	_qf__mod_lti_mod_form:1
	mform_showmore_id_general:1
	mform_showmore_id_modstandardelshdr:0
	mform_isexpanded_id_general:1
	mform_isexpanded_id_privacy:0
	mform_isexpanded_id_modstandardelshdr:0
	mform_isexpanded_id_availabilityconditionsheader:0
	name:qwc
	introeditor[text]:
	introeditor[format]:1
	introeditor[itemid]:781240441
	showtitlelaunch:0
	typeid:0
	toolurl:http://sfa.ck/alsc
	securetoolurl:
	launchcontainer:1
	resourcekey:key
	password:secret
	instructorcustomparameters:
	icon:
	secureicon:
	instructorchoicesendname:1
	instructorchoicesendemailaddr:1
	instructorchoiceacceptgrades:0
	visible:1
	cmidnumber:
	conditiongradegroup[0][conditiongradeitemid]:0
	conditiongradegroup[0][conditiongrademin]:
	conditiongradegroup[0][conditiongrademax]:
	conditionfieldgroup[0][conditionfield]:0
	conditionfieldgroup[0][conditionfieldoperator]:contains
	conditionfieldgroup[0][conditionfieldvalue]:
	showavailability:1
	submitbutton:Guardar cambios y mostrar






*/






}

?>