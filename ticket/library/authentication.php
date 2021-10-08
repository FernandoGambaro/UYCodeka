<?php 
	class Authentication extends Config {
		private $DB;
		private $table = 	[
						"acc" => "tbl_accounts",
						"user" => "tbl_users"
					];
		function __construct(){
			$this->DB = $this->DB_CONNECT();
		}

		public function check_auth($data = array(), $status = 1) {
			try {
					$sql   = $this->DB->prepare("SELECT * FROM ".$this->table['acc']." WHERE username=:username AND password=:password LIMIT 1");
					 $sql->execute(array(":username" => URI::sanitize($data['txt_username']), ":password" => URI::sanitize($data['txt_password'])));
					$getRow = $sql->fetch(PDO::FETCH_ASSOC);
					if($sql->rowCount() > 0) {
						if($status != $getRow['status']) {
							$response = ["response" => "Blocked", "message" => "Your Account has been deactivated!"];
						}else{
							$session = [
									"id"   	 	=> $getRow['accId'],
									'username' 	=> $getRow['username'],
									"isLoggedIn" 	=> true
									];
							self::sessionSet($session);
							$response = ["response" => "Success", "message" => "success", 'role' => $_SESSION['user']['role'] ];
						}
					}else{
						$response = ["response" => "Failed", "message" => "Invalid username or password!"];
					}
					return json_encode($response);	
	
			} catch (PDOException $e) {
				throw new Exception($e->getMessage());
			}
		}

		public function sessionSet($data = []){
			URI::session();
			$_SESSION['user'] = $data;
			return $_SESSION['user'];
		}

		public static function logout(){
			URI::session();
			URI::destroy();
			URI::redirect('../../login.php');
		}

		
	}
	
 ?>