<?php
require_once __DIR__ .'/../../common/funcionesvarias.php';   
require_once __DIR__ .'/../../common/fechas.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('usuarioslicencia');
$obj->Select();


// include header file
$page_title = "Listado licencias";
include_once "header-rejilla.php";

//if(strlen($_GET["codhorario"])>0) { $codcliente=$_GET["codhorario"]; }

// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1
$records_per_page = 6; // set records or rows of data per page
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
            if(strlen(trim($value)) >0 and $key=='desde'){ 
                $obj->Where('desde', explota($value)." 00:00:00", '>=');    
            }elseif(strlen(trim($value)) >0 and $key=='hasta'){ 
                $obj->Where('hasta', explota($value)." 00:00:00", '<=');    
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
                if(strlen(trim($value)) >0 and $key=='desde'){ 
                    $obj->Where('desde', explota($value)." 00:00:00", '>=');    
                }elseif(strlen(trim($value)) >0 and $key=='hasta'){ 
                    $obj->Where('hasta', explota($value)." 00:00:00", '<=');    
                } else {          
                    if(strlen(trim($value)) >0){ 
                        $obj->Where($key, $value, 'LIKE');    
                    }   
                }
            }         
        }

}

$data=array_envia($data); 

$obj->Where('codusuarios', $codusuarios);
$obj->Where('borrado', '0');
$obj->Orden('codlicencia', 'DESC');
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
            <th class="fit"><?php echo _('ACCIÓN');?></th>
            <th><?php echo _('Desde');?></th>
            <th><?php echo _('Hasta');?></th>
            <th class="fit"></th>
        </tr>
    </thead>
    <tbody>
    <input type="hidden" name="numfilas" id="numfilas" value="<?php echo $total_rows; ?>">

<?php
/*Verifico los permisos del usuario logeado*/

$eliminar=verificopermisos('Licenciausuario', 'eliminar', $UserID);
$modificar=verificopermisos('Licenciausuario', 'modificar', $UserID);

foreach($rows as $row){

        echo "<tr class=\"btn-inverse trigger\" data-codlicencia=\"".$row['codlicencia']."\" >";
        echo "<td>";
        // Modificar datos del cliente
        if ( $modificar=="true") {
            echo "<button onclick=\"OpenWindow('licencia/edit.php?codlicencia=" . $row['codlicencia'] . "', 'frame_horario','50%','400', 'close', 'false')\" 
            class='btn btn-warning left-margin btn-xs'>";
            echo "<span class='glyphicon glyphicon-edit'></span> Editar";
            echo "</button>"; 
        }  
        echo "</td>"; 

        echo "<td>";
        echo implota($row['desde']);
        echo "</td>";
        echo "<td>";
        echo implota($row['hasta']);
        echo "</td>";

        echo "<td class=\"fit\">";
        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar('" . $row['codlicencia'] . "');\"></span>";
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
    echo "<div>". _('No se encontraron registros.'). "</div>";
    }
?>
