<?php
//Defino una variable para cargar solo lo necesario en la seccion que corresponde
//siempre antes de cargar el geader.php
$section='log_rejilla';

require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   
require_once __DIR__ .'/../common/sectores.php';   //Array con lista de los sectores del sistema
require_once __DIR__ .'/../common/verificopermisos.php';   

require_once __DIR__ .'/../library/conector/consultas.php';

use App\Consultas;

$obj = new Consultas('logs');
$obj->Select();


$page_title = "Logs de usuarios";

$oidcontacto = isset($_GET['oidcontacto']) ? $_GET['oidcontacto'] : $_POST['oidcontacto'];

include_once "header-rejilla.php";

// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1
$records_per_page = 7; // set records or rows of data per page
$from_record_num = ($records_per_page * $page) - $records_per_page; // calculate for the query limit clause

$alldata="";
$data=array();
$alldata=isset($_GET['alldata']) ? $_GET['alldata'] : null ;
$alldata=array_recibe($alldata);

//var_dump($alldata);
if(is_array($alldata) && !empty($alldata)){
	foreach ($alldata as $key => $value)
	{
		if (!empty($_GET[$key])) {
			$$key=$_GET[$key];
		} else {
		$temp = is_array($value) ? $value : trim($value);
	    $$key = $temp;
        $data[$$key]=$temp;
        //echo "<br>-*->".$key;
            if($key!='oidlog'){
                if(strlen(trim($value)) >0 and $key=='fecultest'){ 
                    $obj->Where($key, explota($value)." 00:00:00");    
                } else {          
                    if(is_numeric($value)){
                        $obj->Where($key, $value);    
                    }elseif(strlen(trim($value)) >0){ 
                        $obj->Where($key, $value, 'LIKE');    
                    }   
                }
            } else {
                $obj->Where($key, $value);    
            }    
	    }
	}
	$data=$alldata;
} else {
        foreach($_REQUEST as $key => $value){
            if($key!='page' and $key!='alldata' and $key != 'seleccionados'){
                $temp = is_array($value) ? $value : trim($value);
                $data[$key] = $$key = $temp;
                //echo "<br>---->".$key; 
                //echo "<br>";               
                if($key!='oidlog'){
                    if(strlen(trim($value)) >0 and $key=='fecultest'){ 
                        $obj->Where($key, explota($value)." 00:00:00");    
                    } else {
                        if(is_numeric($value)){
                            $obj->Where($key, $value);    
                        }elseif(strlen(trim($value)) >0){ 
                            $obj->Where($key, $value, 'LIKE');    
                        }   
                    }
                } else {
                    $obj->Where($key, $value);    
                }
            }         
        }

}
//var_dump($data);

$data=array_envia($data); 

$obj->Orden("fecha" , "DESC");
$obj->Limit($from_record_num, $records_per_page);

$paciente = $obj->Ejecutar();
//echo $paciente["consulta"];
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

//echo $paciente["consulta"];

$target = 'frame_rejilla';
include_once 'pagination.php';

if($total_rows>=0){
?>
    <table class='table table-hover table-responsive table-bordered table-condensed'>
    <thead>
        <tr>
            <th style=" background-color: #5DACCD; color:#fff">Usuario</th>
            <th style=" background-color: #5DACCD; color:#fff">Fecha</th>
            <th style=" background-color: #5DACCD; color:#fff">Detalles</th>
            <th style=" background-color: #5DACCD; color:#fff">IP</th>
            <th style=" background-color: #5DACCD; color:#fff" class="fit"></th>
        </tr>
    </thead>
    <tbody>
    <input type="hidden" name="numfilas" id="numfilas" value="<?php echo $total_rows; ?>">

<?php


foreach($rows as $row){

        echo "<tr class=\"triggerRejilla\" id=\"".$row['oidlog']."\" data-oid=\"".$row['oidlog']."\" 
        data-fecha=\"".$row['fecha']."\" >";
        echo "<td>";
        if(strlen($row['oidcontacto'])>=1){
            $codusuarios = new Consultas('usuarios');
            $codusuarios->Select('codusuarios, nombre, apellido');        
            $codusuarios->Where('', $row['oidcontacto']);
            $codusuarios = $codusuarios->Ejecutar();
            //echo $codusuarios["consulta"];
            $medimpfila = $codusuarios["datos"];
            if(count($medimpfila)>0){
            echo $medimpfila[0]['nombre'].' '.$medimpfila[0]['apellido'];
            }
        }
        echo "</td>";  
        echo "<td>". implota(substr($row['fecha'],0,10)) . "</td>";

        //Tipo de Estudio
        echo "<td>";
        if(strlen($row['hace'])>0){
            echo $row['hace'];
        }
        echo "</td>";
        echo "<td>". $row['ip'] . "</td>";
        // delete user button
        echo "<td>";
        echo "<a href='delete.php?oid=" . $row['oidlog'] . "' class='btn btn-danger delete-object btn-xs'>";
        echo "<span class='glyphicon glyphicon-remove'></span>";
        echo "</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";

}
else{
    echo "<div> No se encontraron registros. </div>";
    }
?>
