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

	public function groupList()
	{
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

}