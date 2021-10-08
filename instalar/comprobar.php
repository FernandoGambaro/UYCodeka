<?php
		$Usuario= $_POST['Usuario'];
		$Password= $_POST['Password'];
		$Servidor= $_POST['Servidor'];
		$BaseDeDatos= $_POST['BaseDeDatos'];   
	    
      if($Usuario!='' and $Password!='' and $Servidor!='' and $BaseDeDatos!='') {
            comprobar($Usuario, $Password, $Servidor, $BaseDeDatos);
      }
       
      function comprobar($Usuario, $Password, $Servidor, $BaseDeDatos) {
      	
		$conexion= @mysqli_connect(@$Servidor, @$Usuario, @$Password) or die('0');
				if ($conexion=='') {
                  echo '0';
            }else{
					$descriptor=@mysqli_query($conexion, "USE ". $BaseDeDatos);
            	if (!$descriptor) {
                  	echo "1";
                  } else {
                  	echo "2";
                  }
            }
      }     
?>