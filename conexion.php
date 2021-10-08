<?php
include("configuro.php");

$conectar=@($GLOBALS["___mysqli_ston"] = mysqli_connect($Servidor, $Usuario, $Password));
mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES utf8"); //Soluciona el tema de las ñ y los tildes

if(!$conectar){
 echo "Error al intentar conectarse con el servidor, Provablemte no exista la base";
 exit();
}
/*/Elegir una BD:*/
if(!@((bool)mysqli_query($conectar, "USE " . $BaseDeDatos))){
 echo "No se pudo conectar correctamente con la Base de datos";
 exit();
}

?>