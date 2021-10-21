<?php 

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;
//field:field_name, value:value, codfactura:nro_factura, numlinea:linea
if(isset($_POST['field']) && isset($_POST['value']) && isset($_POST['codfactura']) && isset($_POST['numlinea'])){

    $nombres = array();
    $valores = array();

    $nombres[] = $_POST['field'];
    $valores[] = $_POST['value'];


    $objTmp = new Consultas('factulineatmp');
    $objTmp->Update($nombres, $valores);
    $objTmp->Where('codfactura', $_POST['codfactura']);
    $objTmp->Where('numlinea', $_POST['numlinea']);
    $resultado=$objTmp->Ejecutar();

    echo 1;
} else{
    echo 0;
}
exit;
?>