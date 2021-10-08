<?php
require_once __DIR__ .'/../../common/funcionesvarias.php';   
require_once __DIR__ .'/../../common/fechas.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('equipos');
$obj->Select();


// include header file
$page_title = "Listado equiposs";
include_once "header-rejilla.php";

//if(strlen($_GET["codequipos"])>0) { $codcliente=$_GET["codequipos"]; }

// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1
$records_per_page = 5; // set records or rows of data per page
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
$obj->Orden('borrado', 'ASC');
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
            <th class="fit"><?php echo _('ACCIÓN');?></th>
            <th class="fit"><?php echo _('Fecha');?></th>
            <th class="fit"><?php echo _('Alias');?></th>
            <th class="fit"><?php echo _('Equipo');?></th>
            <th>&nbsp;<?php echo _('Nº');?></th>
            <th>&nbsp;<?php echo _('Service');?></th>
            <th class="fit"></th>
        </tr>
    </thead>
    <tbody>
    <input type="hidden" name="numfilas" id="numfilas" value="<?php echo $total_rows; ?>">

<?php
/*Verifico los permisos del usuario logeado*/
$tipo = array("Sin&nbsp;definir", "Sin&nbsp;Servicio","Con&nbsp;Mantenimiento", "Mantenimiento&nbsp;y&nbsp;Respaldos");
$eliminar=verificopermisos('EquiposCliente', 'eliminar', $UserID);
$modificar=verificopermisos('EquiposCliente', 'modificar', $UserID);
$dias=array(1=>'Lunes', 2=>'Martes', 3=>'Miércoles', 4=>'Jueves', 5=>'Viernes', 6=>'Sábado',7=>'Domingo' );

foreach($rows as $row){

        echo "<tr class=\"btn-inverse trigger\" data-codequipo=\"".$row['codequipo']."\" >";
        echo "<td>";
        // Modificar datos del cliente
        if ( $modificar=="true") {
            echo "<button onclick=\"OpenWindow('equipos/edit.php?codequipo=" . $row['codequipo'] . "', 'frame_rejilla','98%','350', 'close', 'false')\" 
            class='btn btn-warning left-margin btn-xs'>";
            echo "<span class='glyphicon glyphicon-edit'></span> Editar";
            echo "</button>"; 
        }  
        echo "</td>"; 

        echo "<td>". implota($row['fecha'])."</td>";
        echo "<td class=\"fit\">";
        echo $row['alias'];
        echo "</td>";
        echo "<td class=\"fit\">";
        echo $row['descripcion'];
        echo "</td>";
        echo "<td>";
        echo $row['numero'];
        echo "</td>";
        echo "<td>";
        echo $tipo[$row['service']];
        if($row['service']==3){
            $diasrespaldo=array();
            echo _(' los días ');
            if(strpos($row['diasrespaldo'], '-')!==false){
            $diasrespaldo=explode('-',$row['diasrespaldo']);
            }else {
                for($i=0;$i<strlen($row['diasrespaldo']);$i++){
                    $diasrespaldo[$row['diasrespaldo'][$i]]=$row['diasrespaldo'][$i];
                }                
            }
            foreach($diasrespaldo as $key ){
                if($key!='' and $key!='-'){
                    if($key==0){
                        echo $dias[7]. " ";
                        }else{
                        //    echo $key. " ";
                        echo $dias[$key]. " ";
                        }
                    }
                }
            }
        echo "</td>";
        echo "<td>";
        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar('" . $row['codequipo'] . "', 'equipo');\"></span>";
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
