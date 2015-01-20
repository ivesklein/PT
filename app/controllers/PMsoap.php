<?php
class PMsoap {

	var $hash;
	var $status;

	public function __construct(){
		//$this->hash = $this->login();
		$this->url = "http://".$_SERVER['HTTP_HOST']."/sysworkflow/en/classic/services/wsdl2";
	
	}

	public function login(){
		$return = array();
		if(Auth::check()){
			$user = Auth::user()->pm_id;
			$pass = Auth::user()->pmpass;

			$client = new SoapClient($this->url);
			$params = array(array('userid'=>$user,'password'=>$pass));
			$res = $client->__SoapCall('login',$params);
			
			if($res->status_code==0){
				$this->hash = $res->message;
				$return["ok"] = $this->hash;
				$this->status = true;
			}else{
				$return["error"] = $res->message;
				$this->status = false;
			}

		}else{
			$return["error"] = "no login";
			$this->status = false;
		}
		
		return $return;
	}

	public function roleList(){
		$return = array();

		if($this->status){
			$client = new SoapClient($this->url);
			$params = array(array('sessionId'=>$this->hash));
			$result = $client->__SoapCall('roleList', $params);
			$rolesArray = $result->roles;
			if (is_array($rolesArray))
			    $return["ok"] = $rolesArray;
			else 
			    $return["error"] = $rolesArray->name;
		}else{
			$return["error"] = "no hash";
		}
		return $return;
	}

	public function usersList(){

		$return = array();

		if($this->status){
			$client = new SoapClient($this->url);
			$params = array(array('sessionId'=>$this->hash));
			$result = $client->__SoapCall('userList', $params);
			$usersArray = $result->users;
			if (is_array($usersArray))
			    $return["ok"] = $usersArray;
			else 
			    $return["error"] = $usersArray->name;
		}else{
			$return["error"] = "no hash";
		}
		return $return;
	}

	public function groupList(){
		$return = array();

		if($this->status){
			$client = new SoapClient($this->url);
			$params = array(array('sessionId'=>$this->hash));
			$result = $client->__SoapCall('groupList', $params);
			$groupsArray = $result->groups;
			if (is_array($groupsArray))
			    $return["ok"] = $groupsArray;
			else 
			    $return["error"] = "Error: $groupsArray->guid $groupsArray->name \n";

		}else{
			$return["error"] = "no hash";
		}
		return $return;
	}

	public function newUser($userId, $name, $surname, $email, $role, $pass){
		$return = array();

		$roles = array("PROCESSMAKER_ADMIN","PROCESSMAKER_MANAGER", "PROCESSMAKER_OPERATOR");

		if($this->status){
			$client = new SoapClient($this->url);
			$params = array(array(
				'sessionId'=>$this->hash,
				'userId' => $userId,
			    'firstname'=>$name,
			    'lastname'=>$surname,
			    'email'=>$email, 
			    'role'=>$roles[$role],
			    'password'=>$pass
			    ));
			$result = $client->__SoapCall('createUser', $params);
			if ($result->status_code == 0) 
			    $return["ok"] = $result->userUID; 

			else 
			    $return["error"] = $result->status_code.":".$result->message;

		}else{
			$return["error"] = "no hash";
		}
		return $return;
	}

	public function user2group($userId, $group){
		$return = array();

		if($this->status){
			$client = new SoapClient($this->url);
			$params = array(array(
				'sessionId'=>$this->hash,
				'userId' => $userId,
			    'groupId'=>$group
			    ));

			$result = $client->__SoapCall('assignUserToGroup', $params);
			if ($result->status_code == 0) 
			    $return["ok"] = $result;  
			else 
			    $return["error"] = $result->status_code.":".$result->message;

		}else{
			$return["error"] = "no hash";
		}
		return $return;
	}

	//processlist
	//PT-UAI

	public function processList(){
		$return = array();

		if($this->status){
			$client = new SoapClient($this->url);

			$params = array(array('sessionId'=>$this->hash));
			$result = $client->__SoapCall('processList', $params);
			$processesArray = $result->processes;
			
			$ok = false;

			if ($processesArray != (object) NULL)
			{
			   if (is_array($processesArray))
			   {
			       foreach ($processesArray as $process){

				       	if($process->name=="PT-UAI"){
				       		$var = new Pmvar;
				       		$var->var = "process";
				       		$var->value = $process->guid;
				       		$var->save();
				       		$ok=true;
				       	}
			       }
			   }
			   else
			   {
			       print "Process name: $processesArray->name, Process ID: $processesArray->guid \n";

		       		if($processesArray->name=="PT-UAI"){
			       		$var = new Pmvar;
			       		$var->var = "process";
			       		$var->value = $processesArray->guid;
			       		$var->save();
			       		$ok=true;
			       	}

			   }

			   if($ok==false){
			   		$return["error"] = "Process PT-UAI not found";
			   }else{
			   		$return["ok"] = "Process PT-UAI added to db";
			   }

			}
			else 
			   $return["error"] = "Error: ".$processesArray->name;

		}else{
			$return["error"] = "no hash";
		}
		return $return;
	}


	public function taskList(){
		$return = array();

		if($this->status){
			$client = new SoapClient($this->url);

			$params = array(array('sessionId'=>$this->hash));
			$result = $client->__SoapCall('taskList', $params);
			$tasksArray = $result->tasks;

			$ok=false;
			if ($tasksArray != (object) NULL)
			{
			    foreach ($tasksArray as $task){
			    
			        print "Task name: $task->name, Task ID: $task->guid \n";
					if($task->name=="Ingreso Temas"){
			       		$var = new Pmvar;
			       		$var->var = "task";
			       		$var->value = $task->guid;
			       		$var->save();
			       		$ok=true;
			       	}

				}


				if($ok==false){
					$return["error"] = "Task Ingreso Temas not found";
				}else{
			   		$return["ok"] = "Task Ingreso Temas added to db";
			   	}
			}else 
			    $return["error"] = "There are zero tasks assigned to this user. \n";
		
		}else{
			$return["error"] = "no hash";
		}
		return $return;
	}

	public function caseList(){
		$return = array();

		if($this->status){
			$client = new SoapClient($this->url);

			$params = array(array('sessionId'=>$this->hash));
			$result = $client->__SoapCall('caseList', $params);
			$casesArray = $result->cases;
			if ($casesArray != (object) NULL)
			    $return["ok"] = $casesArray;
			else 
			    $return["error"] = "There are zero cases";
		}else{
			$return["error"] = "no hash";
		}
		return $return;
	}

	public function newCase($vars){
		$return = array();

		if($this->status){
			$client = new SoapClient($this->url);

			 //$name = new variableStruct();
			 /*$vars = array(
			 	'APPLICANT_NAME'=>'John', 
			 	'APPLICANT_LAST'=>'Doe', 
			    'APPLICANT_ID'=>'123456',
			    'APPLICANT_EMAIL'=>'johndoe@example.com',
			    '$APPLICANT_EMPLOYER'=>'Example Corp, Inc.'
			 );*/
			 $aVars = array();
			 foreach ($vars as $key => $val)
			 { 
			      $obj = new variableStruct();
			      $obj->name = $key;
			      $obj->value = $val;
			      $aVars[] = $obj;	 
			 }
			 
			 # Use the functions processList() and taskList() to look up the IDs for the 
			 # process and its first task.
			 $params = array(array(
			 	'sessionId'=>$this->hash, 
			 	'processId'=>Pmvar::whereVar("process")->first()->value, 
			    'taskId'=>Pmvar::whereVar("task")->first()->value, 
			    'variables'=>$aVars
			 ));
			 $result = $client->__SoapCall('newCase', $params);
			 if ($result->status_code == 0)
			      $return["ok"] = array($result->caseId, $result->caseNumber);
			 else 
			      $return["error"] = "Error creating case: ".$result->message;
		
		}else{
			$return["error"] = "no hash";
		}
		return $return;
	}

	public function routeCase($case, $index){
		$return = array();

		if($this->status){
			$client = new SoapClient($this->url);

			$params = array(array(
				'sessionId'=>$this->hash, 
			    'caseId'=>$case, 
			    'delIndex'=>$index));
			$result = $client->__SoapCall('routeCase', $params);
			if ($result->status_code == 0)
			    $return["ok"] = "Case derived: $result->message";
			else
			    $return["error"] = "Error deriving case: $result->message";

		}else{
			$return["error"] = "no hash";
		}
		return $return;

	}

	public function newTema($tema, $alumno1, $alumno2, $profesor){
		$return = array();
		
		$a1 = Student::whereWc_id($alumno1)->first();
		$a2 = Student::whereWc_id($alumno2)->first();
		$p = User::whereWc_id($profesor)->first();

		$array = array(
			 	'tema'=>$tema, 
			 	'nombre1'=>$a1->name, 
			    'apellido1'=>$a1->surname,
			    'run1'=>$a1->run,
			    'email1'=>$a1->wc_id,
			    'nombre2'=>$a2->name, 
			    'apellido2'=>$a2->surname,
			    'run2'=>$a2->run,
			    'email2'=>$a2->wc_id,
			    'profesorguia'=>$p->pm_uid
			 );

		$res = $this->newCase($array);

		if(isset($res["ok"])){
			//route case
			$res2 = $this->routeCase($res["ok"][0],"1");
			$return = $res2;

		}else{
			$return = $res;
		}

		return $return;
	}




}