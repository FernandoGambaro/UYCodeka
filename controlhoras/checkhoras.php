<?php
require_once __DIR__ .'/../common/fechas.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


//if(isset($_POST['ci'])) {


    $totalhoras=$_POST['horas'].":00";
    $codcliente=$_POST['codcliente'];

    if(strlen($_POST['fecha'])>0){
        $ffin= data_last_month_day($_POST['fecha']);
        $fini=data_first_month_day($_POST['fecha']);
    }else{
        $ffin= data_last_month_day(date("Y-m-d"));
        $fini=data_first_month_day(date("Y-m-d"));
    }
    

    $fini.=" 00:00:00";
    $ffin.=" 23:59:59";    

    $objclientes = new Consultas('clientes');
                  
    $objclientes->Select();
    $objclientes->Where('codcliente', $codcliente);
    $clientes = $objclientes->Ejecutar();

    $total_clientes=$clientes["numfilas"];
    if($total_clientes!=''){
    $rowclientes = $clientes["datos"][0]; 
        $horas= $rowclientes["horas"].':00:00';
        if(strlen($horas)<8){
            $horas='0'.$horas;
        }
        $nombre= $rowclientes["empresa"].' - '.$rowclientes["nombre"].' '.$rowclientes["apellido"];	
    }else{
        $horas='00:00:00';
    }

    $obj = new Consultas('horas');
    $obj->Select();
    $obj->Where('codcliente', $codcliente);
    $obj->Where('fecha', $fini, '>=');    
    $obj->Where('fecha', $ffin, '<=');    
    $obj->Where('borrado', '0');    

    $resultado = $obj->Ejecutar();
    //echo $resultado["consulta"];

    $rows = $resultado["datos"];

    if ( $resultado['estado']=='ok' ){ 
        foreach($rows as $row){
            $parcial=$row["horas"].':00';
            $totalhoras=SumaHoras($parcial,$totalhoras);
        }
        $texto="Cliente: ".$nombre."<br>";
        $texto.="Horas asignadas: ".$horas."<br>";
        $texto.="Horas realizadas: ".$totalhoras."<br>";
        
        $restahora=RestaHoras($horas, $totalhoras);
        
        if(strpos($restahora,'-')!==0) {
            $texto.="Restan: ".$restahora;
        } else {
            $texto.="<strong>Estan pasados: ".substr($restahora,1,8)."</strong>";
        }        
        echo $texto;
    } else {
        echo "0"; // fail			
    }
//}
//echo "hola";
?>