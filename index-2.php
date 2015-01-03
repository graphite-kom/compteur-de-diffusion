<?php

	header("Access-Control-Allow-Origin: *");
	// header("Access-Control-Allow-Credentials: true");
	// header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
	
	ini_set('display_errors', 1);
	ini_set('error_reporting', E_ALL);
	
	require_once './libraries/connection_info.php';
	require_once './libraries/cryptlib.php';
	require_once './libraries/idiorm/idiorm.php';
	require_once './libraries/paris/paris.php';
	require_once './libraries/Slim/Slim/Slim.php';
	
	
	// Slim 
	\Slim\Slim::registerAutoloader();
	
	// MySql connection
	$db_toolkit = new DbToolkit;
	ORM::configure("mysql:host=".$db_toolkit->getHost().";dbname=".$db_toolkit->getDbName());
	ORM::configure("username", $db_toolkit->getUserName());
	ORM::configure("password", $db_toolkit->getPassword());
	
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	// Models
	
	class Keys extends Model {
	
	}
	
	
	class LogErrors extends Model {
		
		public static $_table = 'log_errors';
		
	}
	
	
	class AnimRecords extends Model {
		
		public static $_table = 'anim_records_2015';
		
	}
	
	
	class AnimCount extends Model {
		
		public static $_table = 'anim_count';
		
	}
	
	
	class HourlyPlayCount extends Model {
		
		public static $_table = 'hourly_play_count';
		
	}
	
	
	// + - + - + - + - + - + - + - + - + - + - + - + -  
	// secret_key
	define('SECRET_KEY', 'a-fist-full-of-dollars');
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	// functions
		
	function get_json_decoded($json_string = ""){
		
		if(!empty($json_string)){
		
			$object_val = json_decode($json_string);
	
			switch (json_last_error()) {
				
				case JSON_ERROR_NONE:
					$return_value = $object_val;
					break;
					
				case JSON_ERROR_DEPTH:
					$return_value = 'JSON Error - Profondeur maximale atteinte';
					break;
					
				case JSON_ERROR_STATE_MISMATCH:
					$return_value = 'JSON Error - Inadéquation des modes ou underflow';
					break;
					
				case JSON_ERROR_CTRL_CHAR:
					$return_value = 'JSON Error - Erreur lors du contrôle des caractères';
					break;
					
				case JSON_ERROR_SYNTAX:
					$return_value = 'JSON Error - Erreur de syntaxe ; JSON malformé';
					break;
					
				case JSON_ERROR_UTF8:
					$return_value = 'JSON Error - Caractères UTF-8 malformés, probablement une erreur d\'encodage';
					break;
					
				default:
					$return_value = 'JSON Error - Erreur inconnue';
					break;
					
			}
		
		}else{
			
			$return_value = 'JSON Error - json empty';
			
		}
		
		return $return_value;
		
	}
	
	function authentify_request($nim = "", $caisse_num = NULL, $key_date = "", $key_value = "", $post_obj = ""){
		
		if(!empty($nim) && !is_null($caisse_num) && !empty($key_date) && !empty($key_value) && !empty($post_obj)){
			
			$rebuild_key_params = $nim."-".$caisse_num."-".$key_date; 
			
			$rebuild_key_val = hash_hmac('ripemd128', $rebuild_key_params, SECRET_KEY);
			
			if($rebuild_key_val == $key_value){
				
				$json_string = decrypt_postString($rebuild_key_val, $post_obj);
				
				if($json_string !== FALSE){
					
					$local_object = get_json_decoded($json_string);
					
					if(is_object($local_object)){
						
						$return_val = $local_object;
							
					}else{
						
						$return_val = $local_object."\n";
						
						$return_val .= "Could not decode json";
				
					}
					
				}else{
					
					$return_val = "Could not decrypt data string";
						
				}
				
			}else{
				
				$return_val = "Wrong key parameter";
				
			}
			
			
		}else{
			
			$return_val = "Missing parameters";
			
		}
		
		return $return_val;
		
	}
	
	function decrypt_postString($key = "", $postString = ""){
		
		// init a new instance of Crypto Class
		$crypto = new Crypt;
		
		// init with the encryption key
		$result = $crypto->init(substr($key, 0, 8));
		
		// decrypt data
		$decrypted_messagefromflash = $crypto->decrypt($postString);
		
		return $decrypted_messagefromflash;
		
		
	}
	
	function generateRandomString($length = 32) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	function log_error($nim, $caisse_num, $key_date, $key_value, $post_obj, $object_data, $ip_address, $record_date, $random_key){
		
		if(!is_string($object_data)){
			
			$object_data = (string)$object_data;
			
		}	
		
		$error = Model::factory('LogErrors')->create();
		
		$error->nim 		= $nim;
		$error->caisse_num 	= $caisse_num;
		$error->key_date 	= $key_date;
		$error->key_value 	= $key_value;
		$error->post_obj 	= $post_obj;
		$error->object_data = $object_data;
		$error->ip_address 	= $ip_address;
		$error->record_date = $record_date;
		$error->random_key 	= $random_key;
		
		$error->save();
		
	}
	
	/*
	function save_to_db($nim, $caisse_num, $data_array, $record_date){
		
		$return_value = TRUE;
		
		foreach($data_array as $anim_name => $count){
			
			echo $anim_name." - ".$count."\n";
			
			$anim_found = Model::factory('AnimRecords')->where('nim', $nim)->where('caisse_num', $caisse_num)->where('anim_name', $anim_name)->find_one();
			
			if($anim_found !== FALSE){
				
				$found_count = $anim_found->count;
				
				$new_count = ($found_count + $count);
				
				$anim_found->count = $new_count;
				
				$anim_found->last_record = $record_date;
				
				if(!$anim_found->save()){
					
					$return_value = FALSE;
				
				}
				
			}else{
				
				$anim = Model::factory('AnimRecords')->create();
				
				$anim->nim 			= $nim;
				$anim->caisse_num	= $caisse_num;
				$anim->anim_name 	= $anim_name;
				$anim->count 		= $count;
				$anim->last_record 	= $record_date;
				
				if(!$anim->save()){
					
					$return_value = FALSE;
				
				}				
				
			}
			
		}
		
		return $return_value;
		
	}
	*/
	
	function getEffectiveDatetime(){
		
		$current_datetime = date('Y-m-d H:i:s');
		
		$current_unix_time = strtotime($current_datetime);
		
		$made_time = mktime(date("H", $current_unix_time) + 1, 0, 0, date("n", $current_unix_time), date("j", $current_unix_time), date("Y", $current_unix_time));
		
		$new_date_time = date("Y-m-d H:i:s", $made_time);
		
		return $new_date_time;
	
	}
	
	function save_to_db($nim, $caisse_num, $data_array, $record_date){
		
		$effective_datetime = getEffectiveDatetime();
				
		$return_value = TRUE;
		
		foreach($data_array as $anim_name => $count){
			
			echo $anim_name." - ".$count."\n";
			
			// + - + - + - + - + - + - + - + - + - + - + - + - 
			// save to anim_count table
			
			$anim_found = Model::factory('AnimCount')->where('anim_name', $anim_name)->find_one();
			
			if($anim_found !== FALSE){
				
				$found_count = $anim_found->total_play_count;
				
				$new_count = ($found_count + $count);
				
				$anim_count_id = (int)$anim_found->id;
				
				$anim_found->total_play_count = $new_count;
								
				if(!$anim_found->save()){
					
					$return_value = FALSE;
				
				}
				
			}else{
				
				$anim = Model::factory('AnimCount')->create();
				$anim->anim_name 		= $anim_name;
				$anim->total_play_count	= $count;
				
				if(!$anim->save()){
					
					$return_value = FALSE;
				
				}else{
					
					$anim_count_id = (int)$anim->id();
						
				}
				
			}
			
			// + - + - + - + - + - + - + - + - + - + - + - + - 
			// save to hourly_play_count table
			
			if(!empty($anim_count_id)){
				
				$anim_record_found = Model::factory('HourlyPlayCount')->where('anim_count_id', $anim_count_id)->where('record_date', $effective_datetime)->find_one();
				
				if($anim_record_found !== FALSE){
					
					$found_hourly_count = (int)$anim_record_found->hourly_count;
					
					$new_hourly_count = ($found_hourly_count + $count);
					
					// + - + - + - + - + - + - + - + - + - + - + - + - + - 
					
					$machine_list = $anim_record_found->machines;
					
					$machine_list_array = explode("|", $machine_list);
					
					$new_machine_name = $nim."-".$caisse_num;
					
					if(!in_array($new_machine_name, $machine_list_array)){
						
						$machine_list_array[] = $new_machine_name;
							
					}
					
					$new_machine_list = implode("|", $machine_list_array);
					
					// + - + - + - + - + - + - + - + - + - + - + - + - + -
					
					$anim_record_found->hourly_count = $new_hourly_count;
					
					$anim_record_found->machines = $new_machine_list;
					
					if(!$anim_record_found->save()){
					
						$return_value = FALSE;
					
					}
					
				}else{
					
					$hourly_play_count = Model::factory('HourlyPlayCount')->create();
					$hourly_play_count->anim_count_id 	= $anim_count_id;
					$hourly_play_count->hourly_count 	= $count;
					$hourly_play_count->record_date 	= $effective_datetime;
					$hourly_play_count->machines 		= $nim."-".$caisse_num;
					
					if(!$hourly_play_count->save()){
					
						$return_value = FALSE;
					
					}
					
					$hourly_play_count_id = (int)$hourly_play_count->id();
						
				}
			
			}
			
			
		}
		
		return $return_value;
		
	}
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	// app
	
	$app = new \Slim\Slim(array(
		'debug' => true
	));
	
	$app->get('/getkey', function() use ($app){
		
		$app->halt(403);
			
	});
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	
	$app->post('/getkey', function() use ($app){
		
		// CORRECTION :  METTRE EXPRESSION REGULIERE
		
		$nim = filter_var((!empty($app->request->post('nim')))?$app->request->post('nim'):"", FILTER_SANITIZE_STRING);
		
		// CORRECTION :  METTRE EXPRESSION REGULIERE
		
		$caisse_num = (!is_null($app->request->post('caisse_num')))?filter_var($app->request->post('caisse_num'), FILTER_SANITIZE_NUMBER_INT):NULL;

		try {
		
			if(empty($nim) || is_null($caisse_num)){
				throw new Exception("invalid nim or caisse_num parameters", 1);
			}

			$keys_found = Model::factory('Keys')->where('nim', $nim)->where('caisse_num', $caisse_num)->find_many();
			
			foreach($keys_found as $key_found){
				$key_found->delete();
			}
			
			$current_date = date('Y-m-d H:i:s');
			
			$key_params = $nim."-".$caisse_num."-".$current_date; 
			
			$key_val = hash_hmac('ripemd128', $key_params, SECRET_KEY);
			
			$key = Model::factory('Keys')->create();
			$key->nim 			= $nim;
			$key->caisse_num 	= $caisse_num;
			$key->key_value 	= $key_val;
			$key->key_date 		= $current_date;
			$key->save();
			
			$key_generated = substr($key_val, 0, 8);
			
			echo "status_confirmation=Ok&key=$key_generated";	
		}
		catch (Exception $e) {
			error_log("ERROR with nim=$nim numcaisse=$num_caisse : ".var_export($e->getFullMessage(), true), 3, "/var/log/apache2/counter.log");
			$app->halt(403, 'status_confirmation=403');
		}
	});
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	
	$app->get('/record_diffusion', function() use ($app){
		
		$app->halt(403);
			
	});
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	
	$app->post('/record_diffusion', function() use ($app){

		try {
			// CORRECTION :  METTRE EXPRESSION REGULIERE
			$nim = filter_var((!empty($app->request->post('shop')))?$app->request->post('shop'):"", FILTER_SANITIZE_STRING);
			
			// CORRECTION :  METTRE EXPRESSION REGULIERE
			$caisse_num = (!is_null($app->request->post('machine')))?filter_var($app->request->post('machine'), FILTER_SANITIZE_NUMBER_INT):NULL;
			
			$post_obj = (!empty($app->request->post('post_obj')))?$app->request->post('post_obj'):"";
			
			if(empty($nim) || is_null($caisse_num) || empty($post_obj)) {
				throw new Exception("missing posted data", 1);
			}
			// - + - + - + - + - + - + - + - + - + - + -
			
			$record_date = date("Y-m-d H:i:s");
			
			$ip_address = $_SERVER['REMOTE_ADDR'];
			
			$random_key = generateRandomString();
			
			$key_found = Model::factory('Keys')->where('nim', $nim)->where('caisse_num', $caisse_num)->find_one();

			if($key_found == false || empty($key_found->key_date) || empty($key_found->key_value)) {
				throw new Exception("could not find key information", 1);
			}
			// - + - + - + - + - + - + - + - + - + - + -
			
			$key_date = $key_found->key_date;
			$key_value = $key_found->key_value;
			$object_data = authentify_request($nim, $caisse_num, $key_date, $key_value, $post_obj);
			if(is_string($object_data)) {
				throw new Exception("object_data is a string and it's abnormal !!", 1);
			}
			if(!is_object($object_data)){
				throw new Exception("object_data is not an object and it's abnormal !!", 1);
			}
			// - + - + - + - + - + - + - + - + - + - + - 
			
			$local_array = get_object_vars($object_data);
			
			// save_to_db doit renvoyer une exception qui sera catchée ici si problème il y a 
			// si plusieurs requêtes sql sont réalisées, les encapsuler dans une transaction comme l'ex suivant ci possible
			/*
			try {
			    $db->beginTransaction();
			    $db->query('first query');
			    $db->query('second query');
			    $db->commit();
			} catch (Exception $e) {
			    $db->rollback();
			}
			*/
			save_to_db($nim, $caisse_num, $local_array, $record_date);
			// - + - + - + - + - + - + - + - + - + - + - 
		}
		catch (Exception $e) {
			error_log("ERROR with nim=$nim numcaisse=$num_caisse : ".var_export($e->getFullMessage(), true), 3, "/var/log/apache2/counter.log");					
			$app->halt(403);
		}
		
	});
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	
	$app->run();
	
?>