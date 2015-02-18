<?php
class LTI extends BaseController{

	
	public static function check(){
	
		//clave que le damos previamente al cliente
		$ok = true;
		$message = "";
		if(!isset($_POST['oauth_consumer_key'])){
			$ok=false;
			$message="no key";
		}else{
			$oauth_consumer_key = Input::get('oauth_consumer_key');
		}

		if($ok){
			if(!isset($_POST['lti_message_type'])){
				$ok=false;
				$message="no type";
			}else{
				$type = Input::get('lti_message_type');
			}
		}
		//$vtype = $type=="basic-lti-launch-request"?"true":"false";

		if($ok){
			if(!isset($_POST['lti_version'])){
				$ok=false;
				$message="no version";
			}else{
				$version = Input::get('lti_version');
				if($version!="LTI-1p0"){
					$ok=false;
					$message="lti version not sopported";
				}
			}
		}

		//id instancia creada, un curso puede tener varias
		if($ok){
			if(!isset($_POST['resource_link_id'])){
				$ok=false;
				$message="no id_recurso";
			}else{
				$resource_id = Input::get('resource_link_id');
			}
		}

		//id contexto (curso)
		$context_id = Input::get('context_id');

		//no readable user id
		$user_id = Input::get('user_id');

		//rol dentro del curso
		$roles = Input::get('roles');


		if($ok){
			if(!isset($_POST['oauth_nonce'])){
				$ok=false;
				$message="no nonce";
			}else{
				$oauth_nonce = Input::get('oauth_nonce');
			}
		}

		//verifica si el nonce es válido, devuelve string true false

		if($ok){
			$vnonce = Nonce::pass($oauth_nonce,$oauth_consumer_key);
			if($vnonce=="false"){
				$ok=false;
				$message="invalid nonce";
			}
		}
		//$vnonce = Nonce::exist($oauth_nonce);

		//fecha
		$oauth_timestamp = (float)Input::get('oauth_timestamp');
		if($ok){
			$diff = abs((float)time()-$oauth_timestamp)<1200?"true":"false";
			if($diff=="false"){
				$ok=false;
				$message="invalid timestamp ".(abs(time()-$oauth_timestamp));
			}
		}

		if($ok){
			if(!isset($_POST['oauth_signature'])){
				$ok=false;
				$message="no signature";
			}else{
				$oauth_signature = Input::get('oauth_signature');
			}
		}

		//fullname
		$name_full = Input::get('lis_person_name_full');

		if($ok){
			if(!isset($_POST['oauth_signature_method'])){
				$ok=false;
				$message="no signature method";
			}else{
				$method = Input::get('oauth_signature_method');
				if($method!="HMAC-SHA1"){
					$ok=false;
					$message="signature method not supported";
				}
			}
		}
		
		$sig = self::signature();
		if($ok){
			if($sig!=$oauth_signature){
				$ok=false;
				$message="invalid signature";
			}
		}

		$response = array();

		if($ok){
			if(isset($_POST['lis_person_contact_email_primary'])){
				$response['email'] = Input::get('lis_person_contact_email_primary');
			}
			if(isset($_POST['lis_person_name_given'])){
				$response['name'] = Input::get('lis_person_name_given');
			}
			if(isset($_POST['lis_person_name_family'])){
				$response['surname'] = Input::get('lis_person_name_family');
			}
			if(isset($_POST['user_id'])){
				$response['user_id'] = Input::get('user_id');
			}
		}


		if($ok==true){
			$response['status']="ok";
		}else{
			$response['status']="error";
			$response['message']=$message;
		}

		return $response;

	}

	
	public static function secret($consumer){

		//ver consumerkey y buscar en db

		$con = Consumer::whereKey($consumer)->get();
		if(!$con->isEmpty()){
			$secret = $con->first()->secret;
		}else{
			$secret = "";
		}
		//$secret = "b9d5229df69a51be9741fa895e3a51bc";
		return $secret;

	}

	public static function token($consumer){

		//ver consumerkey y buscar en db
		$token = null;
		return $token;

	}

	public static function signature(){

		$oauth_consumer_key = Input::get('oauth_consumer_key');

		$base_string = self::basestring();
		$secret = self::secret($oauth_consumer_key);
		$token = self::token($oauth_consumer_key);

		$key_parts = array(
	      $secret,
	      ($token) ? $token->secret : ""
	    );

	    $key_parts = self::urlencode_rfc3986($key_parts);
	    $key = implode('&', $key_parts);

	    return base64_encode(hash_hmac('sha1', $base_string, $key, true));
	}

	public static function url(){

		$scheme = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on")
              ? 'http'
              : 'https';
    	$http_url = $scheme .
                  '://' . $_SERVER['SERVER_NAME'] .
                  ':' .
                  $_SERVER['SERVER_PORT'] .
                  $_SERVER['REQUEST_URI'];

        $parts = parse_url($http_url);

	    $scheme = (isset($parts['scheme'])) ? $parts['scheme'] : 'http';
	    $port = (isset($parts['port'])) ? $parts['port'] : (($scheme == 'https') ? '443' : '80');
	    $host = (isset($parts['host'])) ? strtolower($parts['host']) : '';
	    $path = (isset($parts['path'])) ? $parts['path'] : '';

	    if (($scheme == 'https' && $port != '443')
	        || ($scheme == 'http' && $port != '80')) {
	      $host = "$host:$port";
	    }

	    return "$scheme://$host$path";

	}

	public static function parameters(){

		  $request_headers = self::get_headers();

	      // Parse the query-string to find GET parameters
	      $parameters = self::parse_parameters($_SERVER['QUERY_STRING']);

	      // It's a POST request of the proper content-type, so parse POST
	      // parameters and add those overriding any duplicates from GET
	      
	        $post_data = self::parse_parameters(
	          file_get_contents('php://input')
	        );
	        $parameters = array_merge($parameters, $post_data);
	      

	      // We have a Authorization-header with OAuth data. Parse the header
	      // and add those overriding any duplicates from GET or POST
	      if (isset($request_headers['Authorization']) && substr($request_headers['Authorization'], 0, 6) == 'OAuth ') {
	        $header_parameters = self::split_header(
	          $request_headers['Authorization']
	        );
	        $parameters = array_merge($parameters, $header_parameters);
	      }

	      // asdf

	       if (isset($parameters['oauth_signature'])) {
		      unset($parameters['oauth_signature']);
		    }

		   return self::build_http_query($parameters);

	}

	public static function basestring(){

		$parts = array(
	      "POST",
	      self::url(),
	      self::parameters()
	    );

	    $parts = self::urlencode_rfc3986($parts);

	    return implode('&', $parts);

	}

	public static function get_headers() {
	    if (function_exists('apache_request_headers')) {
	      // we need this to get the actual Authorization: header
	      // because apache tends to tell us it doesn't exist
	      $headers = apache_request_headers();

	      // sanitize the output of apache_request_headers because
	      // we always want the keys to be Cased-Like-This and arh()
	      // returns the headers in the same case as they are in the
	      // request
	      $out = array();
	      foreach ($headers AS $key => $value) {
	        $key = str_replace(
	            " ",
	            "-",
	            ucwords(strtolower(str_replace("-", " ", $key)))
	          );
	        $out[$key] = $value;
	      }
	    } else {
	      // otherwise we don't have apache and are just going to have to hope
	      // that $_SERVER actually contains what we need
	      $out = array();
	      if( isset($_SERVER['CONTENT_TYPE']) )
	        $out['Content-Type'] = $_SERVER['CONTENT_TYPE'];
	      if( isset($_ENV['CONTENT_TYPE']) )
	        $out['Content-Type'] = $_ENV['CONTENT_TYPE'];

	      foreach ($_SERVER as $key => $value) {
	        if (substr($key, 0, 5) == "HTTP_") {
	          // this is chaos, basically it is just there to capitalize the first
	          // letter of every word that is not an initial HTTP and strip HTTP
	          // code from przemek
	          $key = str_replace(
	            " ",
	            "-",
	            ucwords(strtolower(str_replace("_", " ", substr($key, 5))))
	          );
	          $out[$key] = $value;
	        }
	      }
	    }
	    return $out;
	}

	public static function parse_parameters( $input ) {
	    if (!isset($input) || !$input) return array();

	    $pairs = explode('&', $input);

	    $parsed_parameters = array();
	    foreach ($pairs as $pair) {
	      $split = explode('=', $pair, 2);
	      $parameter = self::urldecode_rfc3986($split[0]);
	      $value = isset($split[1]) ? self::urldecode_rfc3986($split[1]) : '';

	      if (isset($parsed_parameters[$parameter])) {
	        // We have already recieved parameter(s) with this name, so add to the list
	        // of parameters with this name

	        if (is_scalar($parsed_parameters[$parameter])) {
	          // This is the first duplicate, so transform scalar (string) into an array
	          // so we can add the duplicates
	          $parsed_parameters[$parameter] = array($parsed_parameters[$parameter]);
	        }

	        $parsed_parameters[$parameter][] = $value;
	      } else {
	        $parsed_parameters[$parameter] = $value;
	      }
	    }
	    return $parsed_parameters;
	}
	
	public static function split_header($header, $only_allow_oauth_parameters = true) {
	    $params = array();
	    if (preg_match_all('/('.($only_allow_oauth_parameters ? 'oauth_' : '').'[a-z_-]*)=(:?"([^"]*)"|([^,]*))/', $header, $matches)) {
	      foreach ($matches[1] as $i => $h) {
	        $params[$h] = self::urldecode_rfc3986(empty($matches[3][$i]) ? $matches[4][$i] : $matches[3][$i]);
	      }
	      if (isset($params['realm'])) {
	        unset($params['realm']);
	      }
	    }
	    return $params;
	}

	public static function urldecode_rfc3986($string) {
    	return urldecode($string);
  	}

  	public static function urlencode_rfc3986($input) {
	  if (is_array($input)) {
	    return array_map(array('LTI', 'urlencode_rfc3986'), $input);
	  } else if (is_scalar($input)) {
	    return str_replace(
	      '+',
	      ' ',
	      str_replace('%7E', '~', rawurlencode($input))
	    );
	  } else {
	    return '';
	  }
	}

  	public static function build_http_query($params) {
	    if (!$params) return '';

	    // Urlencode both keys and values
	    $keys = self::urlencode_rfc3986(array_keys($params));
	    $values = self::urlencode_rfc3986(array_values($params));
	    $params = array_combine($keys, $values);

	    // Parameters are sorted by name, using lexicographical byte value ordering.
	    // Ref: Spec: 9.1.1 (1)
	    uksort($params, 'strcmp');

	    $pairs = array();
	    foreach ($params as $parameter => $value) {
	      if (is_array($value)) {
	        // If two or more parameters share the same name, they are sorted by their value
	        // Ref: Spec: 9.1.1 (1)
	        // June 12th, 2010 - changed to sort because of issue 164 by hidetaka
	        sort($value, SORT_STRING);
	        foreach ($value as $duplicate_value) {
	          $pairs[] = $parameter . '=' . $duplicate_value;
	        }
	      } else {
	        $pairs[] = $parameter . '=' . $value;
	      }
	    }
	    // For each parameter, the name is separated from the corresponding value by an '=' character (ASCII code 61)
	    // Each name-value pair is separated by an '&' character (ASCII code 38)
	    return implode('&', $pairs);
	}

}

?>