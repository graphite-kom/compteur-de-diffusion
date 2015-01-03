<?php

	ini_set('display_errors', 1);
	ini_set('error_reporting', E_ALL);
	
	require_once '../libraries/connection_info.php';
	require_once '../libraries/idiorm/idiorm.php';
	require_once '../libraries/paris/paris.php';
	require_once '../libraries/Slim/Slim/Slim.php';
	
	
	// Slim 
	\Slim\Slim::registerAutoloader();
	
	// MySql connection
	$db_toolkit = new DbToolkit;
	ORM::configure("mysql:host=".$db_toolkit->getHost().";dbname=".$db_toolkit->getDbName());
	ORM::configure("username", $db_toolkit->getUserName());
	ORM::configure("password", $db_toolkit->getPassword());
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	// Models
	
	class LogErrors extends Model {
		
		public static $_table = 'log_errors';
		
	}
	
	
	class AnimCount extends Model {
		
		public static $_table = 'anim_count';
		
	}
	
	
	class HourlyPlayCount extends Model {
		
		public static $_table = 'hourly_play_count';
		
	}
	
	
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
	
	function generateRandomString($length = 32) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	function checkParam($param = "", $mode = ""){
		
		switch($mode){
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			
			case "nim":
				
				if(preg_match('/^[0-9]{7}$/', $param) === 0){
			
					throw new Exception("Invalid nim parameter : ".var_export($param, true));
					
				}
				
				break;
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			
			case "caisse_num":
				
				if(preg_match('/^[0-9]{1}$/', $param) === 0){
			
					throw new Exception("Invalid caisse_num parameter : ".var_export($param, true));
					
				}
				
				break;
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			
			case "post_obj":
				
				if(empty($param)){
			
					throw new Exception("Invalid post_obj parameter (empty)");
					
				}
				
				break;
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			
			case "cn_fix_id":
				
				if(preg_match('/^[A-Za-z0-9]{64}$/', $param) === 0){
			
					throw new Exception("Invalid cn_fix_id parameter : ".var_export($param, true));
					
				}
				
				break;
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			
			case "public_key":
				
				if(preg_match('/^[A-Za-z0-9]{64}$/', $param) === 0){
			
					throw new Exception("Invalid public_key parameter : ".var_export($param, true));
					
				}
				
				break;
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			
			case "animation_log_mode":
				
				$allowed_modes = array(
					"hourly",
					"daily"
				);
				
				if(!in_array($param, $allowed_modes)){
			
					throw new Exception("Invalid animation_log_mode parameter : ".var_export($param, true));
					
				}
				
				break;
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			
		}
		
		return $param;
		
	}
	
	function getTotalPdvAndEcrans($anim_id = NULL){
		
		$ecrans_array = array();
		
		$pdv_array = array();
		
		// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + -
		
		if(is_null($anim_id)){
			
			$time_limit = mktime(date('H'), date('i'), date('s'), date('m'), date('d')-30, date('Y'));
		
			$date_limit = date('Y-m-d H:i:s', $time_limit);
			
			$records_found = ORM::for_table('hourly_play_count')->where_gte('record_date', $date_limit)->find_many();
			
		}else{
			
			$records_found = ORM::for_table('hourly_play_count')->where('anim_count_id', $anim_id)->find_many();
			
		}
		
		// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
		
		foreach($records_found as $record){
			
			// ecrans_array
			
			$ecrans = explode("|", $record->machines);
			
			foreach($ecrans as $ecran){
				
				if(!in_array($ecran, $ecrans_array)){
					
					$ecrans_array[] = $ecran;
					
				}
				
				// pdv_array	
				
				$tmp_pdv_array = explode("-", $ecran);
				
				$pdv = $tmp_pdv_array[0];
				
				if(!in_array($pdv, $pdv_array)){
					
					$pdv_array[] = $pdv;
					
				}
				
			}
			
		}
				
		// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
		
		$return_array = array(
			"ecrans"	=> $ecrans_array,
			"pdv"		=> $pdv_array
		);
		
		return $return_array;
		
	}
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	// ROOT_PATH
	
	function get_root_path(){
			
		define('ROOT_PATH', str_replace("index.php", "", $_SERVER['SCRIPT_NAME']));

	}
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	// app
	
	$app = new \Slim\Slim(array(
		'debug' => true,
		'templates.path' => './templates'
	));
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	
	$app->get('/', 'get_root_path', function() use ($app){
		
		$anims_found = ORM::for_table('anim_count')->order_by_desc('first_record_date')->find_many();
					
		$app->render('home.php', array(
			'template'		=> 'home',
			'anims_found' 	=> $anims_found, 
			'page_title' 	=> "Liste des animations",
			'root_path' 	=> ROOT_PATH
		));
		
	});
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	
	$app->get('/animation_log/:public_key/:animation_log_mode', 'get_root_path', function($public_key, $animation_log_mode) use ($app){
		
		$local_array = array();
		
		try{
			
			//  Check parameters - animation_log_mode
			$animation_log_mode	= checkParam($animation_log_mode, "animation_log_mode");
			
			//  Check parameters - public_key
			$public_key 		= checkParam($public_key, "public_key");
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			
			// Get animation anim_count
			$anim_count 		= ORM::for_table('anim_count')->where('public_key', $public_key)->find_one();
			
			// Get animation Id
			$anim_id 			= $anim_count->id;
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			
			// Get animation Info
			$anim_data 			= ORM::for_table('hourly_play_count')->where('anim_count_id', $anim_id)->order_by_asc('record_date')->find_many();
			
			// Get Min date
			$min_date 			= ORM::for_table('hourly_play_count')->where('anim_count_id', $anim_id)->min('record_date');
			
			// Get Max date
			$max_date 			= ORM::for_table('hourly_play_count')->where('anim_count_id', $anim_id)->max('record_date');
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			// Nom de l'animation
			$anim_name = $anim_count->anim_name;
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			// Nombre de PDV / Ecrans enregistrés (J-30)
			
			$nb_total_pdv_and_ecrans = getTotalPdvAndEcrans();
			
			// Nombre de pdv enregistrés ce mois-ci (J-30)
			
			$local_array["Nombre de PDV enregistrés (J-30)"] = count($nb_total_pdv_and_ecrans["pdv"]);
			
			// Nombre d'écrans enregistrés ce mois-ci (J-30)
			
			$local_array["Nombre d'écrans enregistrés ce mois-ci (J-30)"] = count($nb_total_pdv_and_ecrans["ecrans"]);
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			// Nombre de PDV / Ecrans enregistrés pour cette animation
			
			$nb_pdv_and_ecrans_per_anim = getTotalPdvAndEcrans($anim_id);	
			
			// Nombre de pdv diffusant cette publicité
			
			$local_array["Nombre de pdv diffusant cette publicité"] = count($nb_pdv_and_ecrans_per_anim['pdv']);
			
			// Nombre d'écrans diffusant cette publicité		
			
			$local_array["Nombre d'écrans diffusant cette publicité"] = count($nb_pdv_and_ecrans_per_anim['ecrans']);
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			// Nombre total de diffusions pour cette publicité
			
			$local_array["Nombre total de diffusions pour cette publicité"] = $anim_count->total_play_count;
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + -
			// Nombre moyen de diffusions par écran sur la période de la campagne
			
			$local_array["Nombre moyen de diffusions par écran sur la période de la campagne"] = ( $anim_count->total_play_count / count($nb_pdv_and_ecrans_per_anim['ecrans']) );
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + -
			// Dates de diffusion
			
			$min_time 		= strtotime($min_date);
			
			$max_time 		= strtotime($max_date);
			
			//
			
			$start_date = date('Y-m-d', $min_time);
			
			$end_date = date('Y-m-d', $max_time);
						
			//
			
			$datediff 		= abs($max_time - $min_time);
			
			$nb_jours		= ceil($datediff/(60*60*24));
			
			$local_array["Dates de diffusion"] = "du ".$start_date." au ".$end_date." - soit ".$nb_jours." jour(s) <sub><em>Jours entamés</em></sub>";
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + -
			// Nombre moyen de diffusions par écran par jour 
			
			$local_array["Nombre moyen de diffusions par écran par jour"] = ( ( $anim_count->total_play_count / count($nb_pdv_and_ecrans_per_anim['ecrans']) ) / $nb_jours);
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + -
			
			$app->render('animation_log.php', array(
				'template'		=> 'details',
				'anim_name'		=> $anim_name,
				'anim_details' 	=> $local_array, 
				'page_title' 	=> $anim_name,
				'root_path' 	=> ROOT_PATH
			));
			 
			 
			
		}catch(Exception $e){
			
			echo $e->getMessage();
			
			// $app->halt(403, $e->getMessage());
			
		}
		
	});
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	
	$app->run();
	
?>