<?php 
	class view {
		public static function make($file){
			if(!empty($file)){
				return include('views/'.$file.'.php');
			}
		}
	}

 ?>