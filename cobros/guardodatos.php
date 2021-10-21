<?php
ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

require_once __DIR__ .'/../classes/class_session.php';
require_once __DIR__ .'/../common/languages.php';
require_once __DIR__ .'/../common/verificopermisos.php'; 

require_once __DIR__ .'/../library/conector/consultas.php';
require_once __DIR__ .'/../common/fechas.php';
use App\Consultas;


if (!$s = new session()) {
	  echo "<h2>"._('Ocurrió un error al iniciar session!')."</h2>";
	  echo $s->log;
	  exit();
  }

  if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
  {
	  //*user is not logged in*/
	  //echo "<script>window.top.location.href='../index.php'; </script>";  
  } else {
	 $loggedAt=$s->data['loggedAt'];
	 $timeOut=$s->data['timeOut'];
	 if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
		 $s->data['act']="timeout";
		  $s->save();  	
			//header("Location:../index.php");	
		  //echo "<script>window.top.location.href='../index.php'; </script>";
		 exit;
	 }
	 $s->data['loggedAt']= time();
	 $s->save();
  }

    $UserID=$s->data['UserID'];

    $arr= $data = array();
    $arr['msg'] = '';
    $restorecibo = 0;

    $nombres = array();
    $valores = array();

    $codrecibo =  $_POST['codrecibo'];

    $nombres[] = 'codcliente';
    $valores[] = $_POST['codcliente'];
    $nombres[] = 'fecha';
    $valores[] = explota($_POST['fecha']);
    $nombres[] = 'moneda';
    $valores[] = $_POST[ 'Amoneda'];
    $nombres[] = 'importe';
    $valores[] = floatval(str_replace(',', '.', $_POST['totalrecibo']));
   $restorecibo =  $totalrecibo =  $_POST['totalrecibo'];
  
//Verifico si existe el recibo en la tabla

$obj = new Consultas('recibos');
$obj->Select();
$obj->Where('codrecibo', $codrecibo, '=');
$paciente = $obj->Ejecutar();

$obj = new Consultas('recibos');
    if($paciente['numfilas']>0){
        $obj->Update($nombres, $valores);
        $obj->Where('codrecibo', $codrecibo, '=');
    }else{
        $nombres[] = 'codrecibo';
        $valores[] =  $_POST['codrecibo'];
        
        $obj->Insert($nombres, $valores);    
    }

$paciente = $obj->Ejecutar();

if($paciente["estado"]=="ok"){
    $arr['msg'] = ' Recibo Guardado <br> ';

    //Guardo las facturas que están en el recibo
    $obj = new Consultas('recibosfacturatmp');
    $obj->Select();
    $obj->Where('codrecibo', $codrecibo);

    $paciente = $obj->Ejecutar();


    $rows = $paciente["datos"];
    if($paciente["numfilas"]>0){

        foreach($rows as $row){

            $nombres = array();
            $valores = array();

            $nombres[] = 'totalfactura';
            $valores[] =  $row['totalfactura'];

            $objTmp = new Consultas('recibosfactura');
            $objTmp->Select();
            $objTmp->Where('codrecibo', $row['codrecibo'], '=');
            $objTmp->Where('codfactura', $row['codfactura'], '=');              
            $resultado=$objTmp->Ejecutar();

            $objTmp = new Consultas('recibosfactura');

            if($resultado['numfilas']>0){
                $objTmp->Update($nombres, $valores);
                $objTmp->Where('codrecibo', $row['codrecibo']);
                $objTmp->Where('codfactura', $row['codfactura']);              
            }else{
                $nombres[] = 'codrecibo';
                $valores[] =  $row['codrecibo'];
                $nombres[] = 'codfactura';
                $valores[] =  $row['codfactura'];

                $objTmp->Insert($nombres, $valores);
            }

            $resul=$objTmp->Ejecutar();    

            $nombres = array();
            $valores = array();
            if($row['totalfactura']<= $restorecibo){
                $nombres[] = 'estado';
                $valores[ ]= '2';

            }else{
                $nombres[] = 'estado';
                $valores[ ]= '3';
            }
            $restorecibo = $restorecibo - $row['totalfactura'];

            $objTmp = new Consultas('facturas');
            $objTmp->Update($nombres, $valores);
            $objTmp->Where('codfactura', $row['codfactura'], '=');
            $resultado=$objTmp->Ejecutar();
            if($resultado['estado']=='ok'){
                $arr['msg'] =$arr['msg'].  ' Estado de factura ' . $row['codfactura'] . ' actualizado <br> ';
            } 


            if($resul['estado']=='ok'){
                $arr['msg'] =$arr['msg'].  ' Detalles de facturas en recibo guardado/s <br> ';
            }
        }
    }
    //Guardo los comprobantes de pago
    $obj = new Consultas('recibospagotmp');
    $obj->Select();
    $obj->Where('codrecibo', $codrecibo);    

    $paciente = $obj->Ejecutar();
    //echo "<br>".$paciente["consulta"]."<br>-->";

    $rows = $paciente["datos"];
    if($paciente["numfilas"]>0){
    //Verifico que exista el registro en la tabla recibospago, 
    // en caso afirmativo lo borro.

    $objTmp = new Consultas('recibospago');
    $objTmp->Select();
    $objTmp->Where('codrecibo', $codrecibo);    

    $paciente = $objTmp->Ejecutar();
    if($paciente['numfilas']>0){
        $objTmp = new Consultas('recibospago');
        $objTmp->Delete();
        $objTmp->Where('codrecibo', $codrecibo);    
        
        $objTmp->Ejecutar();

    }

    // $importetotal=0;
        foreach($rows as $row){
            $nombres = array();
            $valores = array();
        
            $nombres[] = 'codrecibo';
            $valores[] =  $row['codrecibo'];
            $nombres[] = 'tipo';
            $valores[] =  $row['tipo'];
            $nombres[] = 'codentidad';
            $valores[] =  $row['codentidad'];
            $nombres[] = 'numeroserie';
            $valores[] =  $row['numero'];
            $nombres[] = 'moneda';
            $valores[] =  $row['moneda'];
            $nombres[] = 'tipocambio';
            $valores[] =  $row['tipocambio'];
            $nombres[] = 'importe';
            $valores[] =  $row['importe'];
            $importetotal = $importetotal + $row['importe'];
            $nombres[] = 'fecha';
            $valores[] =  $row['fecha'];
            $nombres[] = 'observaciones';
            $valores[] =  $row['observaciones'];

            $objTmp = new Consultas('recibospago');
            $objTmp->Insert($nombres, $valores);
            $resultado=$objTmp->Ejecutar();

        }

        $arr['msg'] =$arr['msg']. ' Detalles de pago/s en recibo guardado/s <br> ';

    }

}


//Si el total de las facturas es igual a la suma de los comprobantes de pago doy por paga la factura.
if($totalrecibo >= $importetotal){
    $arr['msg'] =$arr['msg']. ' Las facturas en el recibo están todas pagas <br> ';
}else{
    $arr['msg'] =$arr['msg']. ' Algunas facturas no están pagas <br> '; 
}

//Eliminar contenido de recibosfacturatmp y recibospagotmp, luego de guardar

$data[] = $arr;

echo json_encode($data);
?>