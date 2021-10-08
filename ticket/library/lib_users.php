<?php 
	class lib_users extends Config {
		private $DB;
		private $table = 	[
						"acc" => "tbl_accounts",
						"user" => "tbl_users"
					];
		function __construct(){
			$this->DB = $this->DB_CONNECT();
		}
		public function showUsers(){
			$data = [];
			$sql = $this->DB->query("SELECT * FROM ".$this->table['user']);
			while ($row = $sql->fetch(PDO::FETCH_ASSOC)) $data[] = $row; 
			return $data;
		}
		public function deleteUser($uid){
			$sql   = $this->DB->prepare("DELETE FROM ".$this->table['user']." WHERE userId = :uid ");
			$sql->execute([":uid" => $uid ]);
			return json_encode(["response" => "success"]);
		}

		public function getUserRow($uid){
			$sql   = $this->DB->prepare("SELECT * FROM ".$this->table['user']." WHERE userId = :uid ");
			$sql->execute([":uid" => $uid ]);
			return json_encode($sql->fetch(PDO::FETCH_ASSOC));
		}

		public function userStatus($data = []){
			$status = ($data['status'] == 'Present' ? 'Absent' : 'Present' );
			$sql   = $this->DB->prepare("UPDATE ".$this->table['user']." SET status = :status WHERE userId = :uid ");
			$sql->execute([":status" => $status, ":uid" => $data['uid'] ]);
			return json_encode(["response" => "success"]);
		}
	}