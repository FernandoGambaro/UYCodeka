<?php
require_once __DIR__ .'/../../common/funcionesvarias.php';

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

if($_POST){

$var='';

    foreach ($_REQUEST as $key => $item)
    {
        
        if($key=='tabla'){           
            $obj = new Consultas($item);
            $obj->Delete();
        }else{
            $obj->Where($key, $item, 'LIKE');
        }
    }

    $algo = $obj->Ejecutar();

	if ( $algo['estado']=='ok' ){ 
		echo _('Borrado con exito');
	}else{
		echo _('Fallo al borrar ->');
	}
}
?>