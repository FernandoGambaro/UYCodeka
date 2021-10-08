<?php
require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


if (is_ajax()) {
    if (isset($_POST["codrecibo"]) && !empty($_POST["codrecibo"])) { //Checks if action value exists
      $codrecibo = $_POST["codrecibo"];

      $obj = new Consultas('recibosSueldos');
      $nom[] = 'borrado';
      $val[] = 1;
      $obj->Update($nom, $val);
      $obj->Where('codrecibo', $codrecibo);
      $resultado= $obj->Ejecutar();
      //echo $resultado['consulta'];
        if ( $resultado['estado']=='ok' ){ 
            $return["msg"] = _('Borrado con exito');
        }else{
            $return["msg"] = _('Fallo al borrar');
        }
    }else{
        $return["msg"] = _('Fallo al borrar');
    } 
    $return["json"] = json_encode($return);
    echo json_encode($return);
}
  
  //Function to check if the request is an AJAX request
  function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
  }

?>
