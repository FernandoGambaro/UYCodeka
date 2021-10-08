<?php 
	Class Config {

		private $DB_HOST = "localhost";
		private $DB_USERNAME="root";
		private $DB_PASSWORD="mcc123mcc";
		private $DB_DATABASE = "attendtracks";
		public $DB_CONN = null;

		public function DB_CONNECT(){
			try{
				$this->DB_CONN = new PDO("mysql:host=".$this->DB_HOST.";dbname=".$this->DB_DATABASE, $this->DB_USERNAME, $this->DB_PASSWORD);
			} catch(PDOException $ex){
				die("Database connection : " . $ex->getMessage());
			}
			return $this->DB_CONN;
		}
	}

?>