<?php
	
	
	class DbToolkit {
		
		// mysql de test
		private $host		= "localhost";
		private $dbname		= "loopstat";
		private $username	= "root";
		private $password	= "YZUaJTrx";
		
		// mysql de prod
		/*
		private $host		= "30683014d58f16d6cc6e55039d743df22224be8e.rackspaceclouddb.com";
		private $dbname		= "db_cmptr-dffsn";
		private $username	= "user_cmptr-dffsn";
		private $password	= "hahSh2kai=Fipu";
		*/
		
		// + - + - + - + - + - + - + - + - + - + - + - 
		// functions 
		
		function getHost(){
			
			return $this->host;
				
		}
		
		function getDbName(){
			
			return $this->dbname;
				
		}
		
		function getUserName(){
			
			return $this->username;
				
		}
		
		function getPassword(){
			
			return $this->password;
				
		}
		
		function x_query($sql){
		
			if(!empty($sql)){
				
				$mysqli = new mysqli("p:".$this->host, $this->username, $this->password, $this->dbname);
				
				// Verifiy connection
				if ($mysqli->connect_errno) {
					printf("Connection error : %s\n", $mysqli->connect_error);
					exit();
				}
				
				if ($mysqli->query($sql) !== TRUE) {
					printf("Error message : %s\n", $mysqli->error);
				}
				
				$mysqli->close();
				
			}
			
		}
		
	}
	
?>