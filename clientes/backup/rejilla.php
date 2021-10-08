<?php
require_once __DIR__ .'/../../common/funcionesvarias.php';   
require_once __DIR__ .'/../../common/fechas.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('respaldospc');
$obj->Select();


// include header file
$page_title = "Listado respaldos";
include_once "header-rejilla.php";


// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1
$records_per_page = 9; // set records or rows of data per page
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
            if(strlen(trim($value)) >0 and $key=='fechaini'){ 
                $obj->Where('fecha', explota($value)." 00:00:00", '>=');    
            }elseif(strlen(trim($value)) >0 and $key=='fechafin'){ 
                $obj->Where('fecha', explota($value)." 00:00:00", '<=');    
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
                } else {          
                    if(strlen(trim($value)) >0){ 
                        $obj->Where($key, $value, 'LIKE');    
                    }   
                }
            }         
        }

}

$data=array_envia($data); 

$obj->Where('codcliente', $codcliente);
$obj->Orden("fecha" , "DESC");
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
            <th><?php echo _('Fecha');?></th>
            <th><?php echo _('Tarea');?></th>
            <th><?php echo _('Equipo/Usuario');?></th>
            <th><?php echo _('Versión');?></th>
            <th><?php echo _('Errores');?></th>
            <th><?php echo _('Proc.');?></th>
            <th><?php echo _('Resp');?></th>
            <th><?php echo _('Tamaño');?></th>

        </tr>
    </thead>
    <tbody>
    <input type="hidden" name="numfilas" id="numfilas" value="<?php echo $total_rows; ?>">

<?php
/*Verifico los permisos del usuario logeado*/
$tipo = array( 0=>"Activo", 1=>"Borrado");
$eliminar=verificopermisos('proyectos', 'eliminar', $UserID);
$modificar=verificopermisos('proyectos', 'modificar', $UserID);

foreach($rows as $row){

        echo "<tr class=\"btn-inverse trigger\" data-codrespaldos=\"".$row['codrespaldos']."\" >";

        echo "<td>". implota(substr($row['fecha'], 0,10))." - ".substr($row['fecha'], 11,10)."</td>";
        echo "<td>";
        echo $row['tarea'];
        echo "</td>";
        echo "<td>";
        echo $row['usuario'];
        echo "</td>";
        echo "<td>";
        echo $row['version'];
        echo "</td>";
        echo "<td>";
        echo $row['errores'];
        echo "</td>";
        echo "<td>";
        echo $row['procesados'];
        echo "</td>";
        echo "<td>";
        echo $row['respaldados'];
        echo "</td>";
        echo "<td>";
        echo $row['tamano'];
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
