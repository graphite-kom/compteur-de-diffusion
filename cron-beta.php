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
	
	function getMinDateTime(){
		
		$min_date_record = ORM::for_table('anim_records_2015')->min('last_record');
	
		$min_date_time = strtotime($min_date_record);
		
		$min_made_time = mktime(date("H", $min_date_time), 0, 0, date("n", $min_date_time), date("j", $min_date_time), date("Y", $min_date_time));
		
		$min_new_date_time = date("Y-m-d H:i:s", $min_made_time);
		
		return $min_new_date_time;
		
	}
	
	function getMaxDateTime(){
		
		return date("Y-m-d H:i:s");
		
	}
	
	function getInterval($pointer_date_time){
		
		// + - + - + - + - + - + - + - + - + - + - + - 
		// Interval Minimum
		
		$interval_array["min"] = $pointer_date_time;
		
		// + - + - + - + - + - + - + - + - + - + - + - 
		// Interval Maximum
		
		$time = strtotime($pointer_date_time);
		
		$made_time = mktime(date("H", $time), 59, 59, date("n", $time), date("j", $time), date("Y", $time));
		
		$interval_array["max"] = date("Y-m-d H:i:s", $made_time);	
		
		// + - + - + - + - + - + - + - + - + - + - + - 
		// Interval Maximum
		
		$new_made_time = mktime(date("H", $time) + 1, 0, 0, date("n", $time), date("j", $time), date("Y", $time));
		
		$interval_array["new_date_time"] = date("Y-m-d H:i:s", $new_made_time);	
		
		// + - + - + - + - + - + - + - + - + - + - + - 
		
		return $interval_array;
		
			
	}
	
	// + - + - + - + - + - + - + - + - + - + - + - + - 
	// Script
	
	// time_start
	$time_start = microtime(true);
	
	// Min select date time
	$min_select_date_time = getMinDateTime();
	
	// Max select date time
	$max_select_date_time = getMaxDateTime();
	
	// + - + - + - + - + - + - + - + - + - + - + - + - + - 
	
	$db_toolkit->x_query("LOCK TABLES anim_records_2015 READ;");
	
	// + - + - + - + - + - + - + - + - + - + - + - + - + - 
	
	
	$anim_records = ORM::for_table('anim_records_2015')->distinct()->select('anim_name')->where_lte('last_record', $max_select_date_time)->order_by_asc('anim_name')->find_many();
	
	
	foreach($anim_records as $anim_record){
		
		$pointer_date_time = $min_select_date_time;
		
		$anim_name = $anim_record->anim_name;
		
		$sum = ORM::for_table('anim_records_2015')->where('anim_name', $anim_name)->where_lte('last_record', $max_select_date_time)->sum('count');
		
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
		
		while($pointer_date_time <= $max_select_date_time){
			
			$interval_array = getInterval($pointer_date_time);
			
			$hourly_count = ORM::for_table('anim_records_2015')->where('anim_name', $anim_name)->where_lte('last_record', $interval_array["max"])->where_gte('last_record', $interval_array["min"])->sum('count');
			
			if(!is_null($hourly_count)){
				
				echo " + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - \n";
				
				echo $interval_array["min"]." to ".$interval_array["max"]." => ";
				
				var_dump($hourly_count);
				
				// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
				
				$machine_list = ORM::for_table('anim_records_2015')->where('anim_name', $anim_name)->where_lte('last_record', $interval_array["max"])->where_gte('last_record', $interval_array["min"])->group_by('nim')->group_by('caisse_num')->find_many();
				
				$machine_array = array();
				
				foreach($machine_list as $machine){
					
					if($machine->caisse_num != 0){
						
						$machine_unit = $machine->nim."-".$machine->caisse_num;
						
						$machine_array[] = $machine_unit;
						
						echo " - - - - - - - - - - - - - - - - - - - - - - - - - - - \n";
					
						echo $machine->nim."-".$machine->caisse_num."\n";	
					
					}
						
				}
				
				$machine_list_string = implode("|", $machine_array);
				
				// + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - 
				// saving to table hourly_play_count
				
				$hourly_play_count = Model::factory('HourlyPlayCount')->create();
				$hourly_play_count->anim_count_id 	= $anim_count_id;
				$hourly_play_count->hourly_count 	= $hourly_count;
				$hourly_play_count->record_date 	= $interval_array['max'];
				$hourly_play_count->machines 		= $machine_list_string;
				$hourly_play_count->save();
				
				$hourly_play_count_id = (int)$hourly_play_count->id();
				
			}	
			
			$pointer_date_time = $interval_array["new_date_time"];
			
		}
		
	}
		
	$db_toolkit->x_query("UNLOCK TABLES;");
	
	$delete = ORM::for_table('anim_records_2015')->where_lte('last_record', $interval_array["max"])->delete_many();
	
	$time_end = microtime(true);
	echo " + - + - + - + - + - + - + - + - + - + - + - + - + - + - + - \n";
	echo "execution time \n";
	
	$execution_time = round (($time_end - $time_start), 3);	
	
	var_dump($execution_time);
	
?>