<?php
	
	header("Access-Control-Allow-Origin: *");
	// header("Access-Control-Allow-Credentials: true");
	// header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
	
	
	ini_set('display_errors', 1);
	ini_set('error_reporting', E_ALL);
	
	require_once './libraries/connection_info.php';
	require_once './libraries/idiorm/idiorm.php';
	require_once './libraries/paris/paris.php';
	
	
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
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	// functions
	function log_error($nim = "", $caisse_num = "", $key_date, $key_value, $post_obj, $object_data, $ip_address, $record_date, $random_key){
		
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
	
	function generateRandomString($length = 32) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	$ip_address 	= $_SERVER['REMOTE_ADDR'];
		
	$object_data	= "AJAX not supported";
	
	// log_error($nim = "", $caisse_num = "", $key_date, $key_value, $post_obj, $object_data, $ip_address, $record_date, $random_key);
	log_error($_POST["shop"], $_POST["machine"], "", "", $_POST["post_obj"], $object_data, $ip_address, date('Y-m-d H:i:s'), generateRandomString());
	
	// header("Content-type: text/javascript");
	
	// echo "callBackFunc('OK');";
	
	header("Content-Type:text/plain");
	
	// echo "Ok";
	
	print_r($_POST);
	
?>