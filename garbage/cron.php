#!/usr/bin/php -ddisplay_errors=E_ALL
<?php
	
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
	// functions
	
	// ...
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	// Script
	
	// time_start
	$time_start = microtime(true);
	
	// + - + - + - + - + - + - + - + - + - + - + - + - + - 
	
	$db_toolkit->x_query("LOCK TABLES anim_records_2015 READ;");
	
	// + - + - + - + - + - + - + - + - + - + - + - + - + - 
	
	$current_datetime = date('Y-m-d H:i:s');
	
	$anim_records = ORM::for_table('anim_records_2015')->distinct()->select('anim_name')->order_by_asc('anim_name')->find_many();
	
	if(!empty($anim_records)){
	
		foreach($anim_records as $anim_record){
			
			$anim_name = $anim_record->anim_name;
			
			$sum = ORM::for_table('anim_records_2015')->where('anim_name', $anim_name)->sum('count');
			
			$anim_found = Model::factory('AnimCount')->where('anim_name', $anim_name)->find_one();
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			// saving to table anim_count
			if($anim_found !== FALSE){
				
				$anim_count_id = (int)$anim_found->id;
				
				$play_count = ($anim_found->total_play_count + $sum);
				
				$anim_found->total_play_count = $play_count;
				$anim_found->save();
				
			}else{
				
				$anim = Model::factory('AnimCount')->create();
				$anim->anim_name = $anim_name;
				$anim->total_play_count = $sum;
				$anim->save();
				
				$anim_count_id = (int)$anim->id();
					
			}
			
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			// machine_list
				
			$machine_list = ORM::for_table('anim_records_2015')->where('anim_name', $anim_name)->group_by('nim')->group_by('caisse_num')->find_many();
			
			$machine_array = array();
			
			foreach($machine_list as $machine){
				
				if($machine->caisse_num != 0){
					
					$machine_unit = $machine->nim."-".$machine->caisse_num;
					
					$machine_array[] = $machine_unit;
					
				}
					
			}
			
			$machine_list_string = implode("|", $machine_array);
			
			// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
			// saving to table hourly_play_count
						
			$hourly_play_count = Model::factory('HourlyPlayCount')->create();
			$hourly_play_count->anim_count_id 	= $anim_count_id;
			$hourly_play_count->hourly_count 	= $sum;
			$hourly_play_count->record_date 	= $current_datetime;
			$hourly_play_count->machines 		= $machine_list_string;
			$hourly_play_count->save();
			$hourly_play_count_id = (int)$hourly_play_count->id();
			
		}
		
		
			
	}	
	
	$db_toolkit->x_query("
		DELETE
		FROM anim_records_2015
	");
	
	$db_toolkit->x_query("UNLOCK TABLES;");
	
	// $delete = ORM::for_table('anim_records_2015')->delete_many();
	
	// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + -
	// execution time
	$time_end = microtime(true);
	echo " + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - \n";
	echo "execution time \n";
	$execution_time = round (($time_end - $time_start), 3);	
	var_dump($execution_time);
	
?>