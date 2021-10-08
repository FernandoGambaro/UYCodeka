<?php
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('recibosSueldos');
$obj->Select();



//$codcliente=isset($_REQUEST["codcliente"]) ? $_REQUEST["codcliente"] : '';

// include header file
$page_title = "Listado recibos de sueldos";

include_once "header-rejilla.php";

$nempleado='';
$alldata="";
$data=array();
$alldata=isset($_GET['alldata']) ? $_GET['alldata'] : null ;
$alldata=array_recibe($alldata);

$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1

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
            if(strlen(trim($value)) >0 and $key=='fechaini'){ 
                $obj->Where('fecha', explota($value)." 00:00:00", '>=');    
            }elseif(strlen(trim($value)) >0 and $key=='fechafin'){ 
                $obj->Where('fecha', explota($value)." 00:00:00", '<=');    
            }elseif(strlen(trim($value)) >0 and $key=='nempleado'){ 
                $obj->Where('nempleado', $value, '=');    
            } else {          
                if(strlen(trim($value)) >0){ 
                    $obj->Where($key, $value, 'LIKE');    
                }   
            }         
        }
	}
	$data=$alldata;
} else {
        foreach($_REQUEST as $key => $value){
            if($key!='page' and $key!='alldata'){
                $temp = is_array($value) ? $value : trim($value);
                $data[$key] = $$key = $temp;
                if(strlen(trim($value)) >0 and $key=='fechaini'){ 
                    $obj->Where('fecha', explota($value)." 00:00:00", '>=');    
                }elseif(strlen(trim($value)) >0 and $key=='fechafin'){ 
                    $obj->Where('fecha', explota($value)." 00:00:00", '<=');    
                }elseif(strlen(trim($value)) >0 and $key=='nempleado'){ 
                    $nempleado=$value;
                    $obj->Where('nempleado', $value, '=');    
                } else {          
                    if(strlen(trim($value)) >0){ 
                        if(is_numeric($value)) {
                            $obj->Where($key, $value);    
                        }else{
                            $obj->Where($key, $value, 'LIKE');    
                        }
                    }   
                }
            } elseif($key=='page' and $value!=''){
                $page=$value;
            }        
        }

}

// for pagination purposes
$records_per_page = 8; // set records or rows of data per page
$from_record_num = ($records_per_page * $page) - $records_per_page; // calculate for the query limit clause

$data=array_envia($data); 

$obj->Where('borrado', '0');    
$obj->Orden("fecha" , "DESC");
$obj->Limit($from_record_num, $records_per_page);
//var_dump($obj);
$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

// include pagination file
include_once 'pagination.php';

// check if more than 0 record found
if($total_rows>=0 and (strlen($nempleado)>1 or $UserTpo==2 or $UserTpo==100) ){
?>
<div class="table-responsive">
    <table class='table table-hover table-responsive table-bordered table-condensed ' id="rejilla">
    <thead class="bg-primary">
        <tr>
            <th class="fit"><?php echo _('ACCIONES');?></th>
            <th><?php echo _('Usuario');?></th>
            <th class="fit"><?php echo _('Subido');?></th>
            <th class="fit"><?php echo _('Mes');?></th>
            <th class="fit"><?php echo _('Año');?></th>
            <th><?php echo _('Tipo');?></th>
            <th><?php echo _('Medio de pago');?></th>
            <th class="fit"><?php echo _('Cta');?></th>
            <th class="fit"></th>
        </tr>
    </thead>
    <tbody>
    <input type="hidden" name="numfilas" id="numfilas" value="<?php echo $total_rows; ?>">

<?php
/*Verifico los permisos del usuario logeado*/
$eliminar=verificopermisos('recibosdesueldo', 'eliminar', $UserID);


foreach($rows as $row){

        echo "<tr class=\"btn-inverse trigger\" data-codrecibo=\"".$row['codrecibo']."\" >";
        echo "<td>";
        // edit user button
        echo "<button onclick=\"ver('" . $row['archivo'] . "')\" 
        class='btn btn-secondary left-margin btn-xs'>";
        echo "<span class='glyphicon glyphicon-edit'></span> Detalles";
        echo "</button>";
    
        echo "</td>";
if($row['visto']==0){
    $estilo='style="font-weight:bold"';
}else{
    $estilo='';
}
        echo "<td class=\"fit\" ".$estilo.">";
        if(strlen($row['empleado'])>0 or strlen($row['codusuarios'])>0 or strlen($row['ci'])>0){
            $objusuarios = new Consultas('usuarios');
            $objusuarios->Select();
            if(strlen($row['empleado'])>0){
                $objusuarios->Where('empleado', $row['empleado']);
            }elseif(strlen($row['ci'])>0){
                $objusuarios->Where('ci', $row['ci'], '=');
            }else{
                $objusuarios->Where('codusuarios', $row['codusuarios']);
            }
            //$objusuarios->Where('borrado','0' );
            $usuarios = $objusuarios->Ejecutar();
            //echo $paciente["consulta"];
            $total_usuarios=$usuarios["numfilas"];
            
            if($total_usuarios>0){
                $rowusuarios = $usuarios["datos"][0];
                echo $rowusuarios['nombre'].'&nbsp;'.$rowusuarios['apellido'];
            }        
        }
        echo "</td>";
        echo "<td>". implota(substr($row['fecha'],0,10)). "</td>";

        echo "<td>".mes($row['mes'])." </td>";
        echo "<td class=\"fit\">".$row['anio']."</td>";
        echo "<td>".$row['tipoliq']." </td>";
        echo "<td class=\"fit\">".$row['mediopago']."</td>";
        echo "<td>".$row['cta']." </td>";

        echo "<td>";
        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar('" . $row['codrecibo'] . "');\"></span>";
            echo "</a>";
        } else {
            echo "";
        }

        echo "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
}

// if there are no user
else{
    echo "<div>"._('No se encontraron registros.')." </div>";
    }
?>
