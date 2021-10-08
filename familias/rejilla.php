<?php
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('familias');
$obj->Select();



//$codcliente=isset($_REQUEST["codcliente"]) ? $_REQUEST["codcliente"] : '';

// include header file
$page_title = "Listado familias";

include_once "header-rejilla.php";


$alldata="";
$data=array();
$alldata=isset($_GET['alldata']) ? $_GET['alldata'] : null ;
$alldata=array_recibe($alldata);

// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1
$records_per_page = 15; // set records or rows of data per page
$from_record_num = ($records_per_page * $page) - $records_per_page; // calculate for the query limit clause


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
            }elseif(strlen(trim($value)) >0 and $key=='codusuarios'){ 
                $obj->Where('codusuarios', $value, '=');    
            }elseif(strlen(trim($value)) >0 and $key=='codcliente'){ 
                $obj->Where('codcliente', $value, '=');    
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
                }elseif(strlen(trim($value)) >0 and $key=='codusuarios'){ 
                    $obj->Where('codusuarios', $value, '=');    
                }elseif(strlen(trim($value)) >0 and $key=='codcliente'){ 
                    $obj->Where('codcliente', $value, '=');    
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


$data=array_envia($data); 

$obj->Where('borrado', '0');    

$obj->Limit($from_record_num, $records_per_page);

$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

// include pagination file
include_once 'pagination.php';

// check if more than 0 record found
if($total_rows>=0){
?>
<div class="table-responsive">
    <table class='table table-hover table-responsive table-bordered table-condensed ' id="rejilla">
    <thead class="bg-primary">
        <tr>
            <th class="fit"><?php echo _('ACCIONES');?></th>
            <th class="fit"><?php echo _('Código');?></th>
            <th><?php echo _('Descripción');?></th>
            <th class="fit"></th>
        </tr>
    </thead>
    <tbody>
    <input type="hidden" name="numfilas" id="numfilas" value="<?php echo $total_rows; ?>">

<?php
/*Verifico los permisos del usuario logeado*/
$eliminar=verificopermisos('FamiliasArticulos', 'eliminar', $UserID);


foreach($rows as $row){

        echo "<tr class=\"btn-inverse trigger\" data-codfamilia=\"".$row['codfamilia']."\" >";
        echo "<td>";
        // edit user button
        echo "<button onclick=\"OpenWindow('edit.php?codfamilia=" . $row['codfamilia'] . "', '#frame_rejilla','500','200')\" 
        class='btn btn-secondary left-margin btn-xs'>";
        echo "<span class='glyphicon glyphicon-edit'></span> Detalles";
        echo "</button>";
        echo "<td>". $row['codfamilia']. "</td>";
        echo "<td>".$row['nombre']." </td>";
        echo "<td>";
        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar('" . $row['codfamilia'] . "');\"></span>";
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
