<?php 	
	Class URI {
		public static function PHP_URL_PATH() {
		    return $_SERVER['REQUEST_SCHEME'].'://' . $_SERVER['HTTP_HOST'] . '/' . explode('/', $_SERVER['REQUEST_URI'])[1];
		}
		public static function BASE_URL(){
			echo self::PHP_URL_PATH();
		}
		public static function style($FILES_NAME) {
			if(!empty($FILES_NAME))
			echo self::PHP_URL_PATH() . "/assets/stylesheets/".$FILES_NAME.".css"; 
		}
		public static function script($FILES_NAME) {
			if(!empty($FILES_NAME))
			echo self::PHP_URL_PATH() . "/assets/scripts/".$FILES_NAME.".js"; 
		}
		public static function sanitize($str){
		  $str = trim($str);
		  if (get_magic_quotes_gpc()) $str = stripslashes($str);
		  return htmlentities($str);
		}
		public static function session(){
			return session_start();	
		}
		public static function destroy(){
			return session_destroy();	
		}
		public static function redirect($page){
			return header("location:".$page);	
		}

	}
 ?>		
