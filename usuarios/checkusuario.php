<?php

require_once '../library/conector/consultas.php';
use App\Consultas;


//if(isset($_POST['ci'])) {
 
    $ci=$_POST['ci'];

    $obj = new Consultas('contactos');
    $obj->Select();
    $obj->Where('ci', $ci, 'LIKE');
    $resultado = $obj->Ejecutar();
//echo $resultado["datos"][0]['nombre'].$resultado['estado']."<br>---->".$resultado['consulta'];

    if ( $resultado['estado']=='ok' ){ 
        echo $resultado["datos"][0]['oid'];
    } else {
        echo "0"; // fail			
    }
//}
//echo "hola";
?>