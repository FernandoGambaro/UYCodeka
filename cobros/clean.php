<?php
require_once __DIR__ .'/../common/funcionesvarias.php';

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

if($_POST){

$var='';
$tabla='';
    foreach ($_REQUEST as $key => $item)
    {
        
        if($key=='tabla'){           
            $obj = new Consultas($item);
            $obj->Delete();
            if($item=='recibosfacturatmp'){
                $tabla='recibosfacturatmp';
            }
        }else{
            $obj->Where($key, $item, '=');
            if($key=='codrecibo'){
                $codrecibo=$item;
            }
        }

    }


    if($tabla=='recibosfacturatmp'){
        $objfacturatmp= new Consultas('recibosfacturatmp');
        $objfacturatmp->Select();
        $objfacturatmp->Where('codrecibo', $codrecibo, '=');
        $objResp = $objfacturatmp->Ejecutar();
        $rows = $objResp['datos'];

        foreach( $rows as $row){

            $nombres = array();
            $valores = array();
            $nombres[] = 'estado';
            $valores[ ]= '1';
            $objTmp = new Consultas('facturas');
            $objTmp->Update($nombres, $valores);
            $objTmp->Where('codfactura', $row['codfactura'], '=');
            $resultado=$objTmp->Ejecutar();
        }
    }

    $algo = $obj->Ejecutar();

	if ( $algo['estado']=='ok' ){ 
		echo _('Borrado con exito ').$algo['consulta'];
	}else{
		echo _('Fallo al borrar ->');
	}
}
?>