<?php
require_once __DIR__ .'/../../common/funcionesvarias.php';   
require_once __DIR__ .'/../../common/fechas.php';   
require_once __DIR__ .'/../../common/sectores.php';   //Array con lista de los sectores del sistema
require_once __DIR__ .'/../../common/verificopermisos.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('biometric');

$obj->Select();


$search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±");
$replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ");

// include header file
$page_title = "Listado equipos biometricos";
include_once "header-rejilla.php";
$page=1;

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
            if(strlen(trim($value)) >0){ 
                if(is_numeric($value)){
                    $obj->Where($key, $value);    
                }else{
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
                if(strlen(trim($value)) >0 and $value!='0'){ 
                    if(is_numeric($value)){
                        $obj->Where($key, $value);    
                    }else{
                        $obj->Where($key, $value, 'LIKE');    
                    }
                }   
            }elseif($key=='page'){
                $page = $value;
            }         
        }
}

// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1
$records_per_page = 10; // set records or rows of data per page
$from_record_num = ($records_per_page * $page) - $records_per_page; // calculate for the query limit clause


$data=array_envia($data); 
$obj->Orden("lugar" , "DESC");
$obj->Orden("borrado" , "DESC");
$obj->Limit($from_record_num, $records_per_page);

$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

// include pagination file
include_once 'pagination.php';

$eliminar=verificopermisos('biometric', 'eliminar', $UserID);

// check if more than 0 record found
if($total_rows>=0){
?>
    <table class='table table-hover table-responsive table-bordered table-condensed' id="rejilla">
    <thead class="bg-primary">
        <tr>
            <th colspan="2" class="fit"><?php echo _("ACCIÓN");?></th>
            <th><?php echo _("Equipo");?></th>
            <th><?php echo _("Ubicacion/Lugar");?></th>
            <th><?php echo _("WEB/IP");?></th>            
            <th><?php echo _("Estado");?></th>            
            <th colspan="3" class="fit"></th>
        </tr>
    </thead>
    <tbody>
    <input type="hidden" name="numfilas" id="numfilas" value="<?php echo $total_rows; ?>">

<?php
$estado=array(0=>'Activo', 1=>'Baja');
foreach($rows as $row){
    if ($row['borrado'] ==1) { $fondolinea="danger"; } else { $fondolinea=""; }
    
        echo "<tr class=\"triggerRejilla ".$fondolinea."\" data-codbiometric=\"".$row['codbiometric']."\" >";
        echo "<td>";

        if($row['internal_id']==0) {
            ?>
                <a href="#"><img id="botonBusqueda" src="../../library/images/comerce.png" width="16" height="16" border="0" onClick="act_db('<?php echo $row['codbiometric']?>')" title="Actualizar Usuarios a Device"></a>
            <?php
            } else {
            ?>
                <a href="#"><img id="botonBusqueda" src="../../library/images/biometric-attendance-2.png" width="25" height="25" onClick="AllUserToDevice('<?php echo $row['codbiometric']?>')" title="Agregar todos los usuarios nuevos al lector"></a></div></td>
            <?php
            }
        echo "</td>";
        echo "<td>";
        ?>
        <img id="botonBusqueda" src="../../library/images/biometric-attendance-20x20.png" width="16" height="16" border="0" onClick="descargar_info('<?php echo $row['codbiometric'];?>')" title="Descargar marcas del equipo">
        <?php
        echo "</td>";
        echo "<td>".$row['nombre']."</td>";
        echo "<td>".$row['lugar']."</td>";
        echo "<td>".$row['direccionip']." </td>";
        echo "<td>".$estado[$row['borrado']]."</td>";

        echo "<td>";
        echo "<button onclick=\"OpenWindow('edit.php?codbiometric=" . $row['codbiometric'] . "', 'frame_rejilla','98%','98%')\" 
        class='btn btn-warning left-margin btn-xs'>";
        echo "<span class='glyphicon glyphicon-edit'></span> Editar";
        echo "</button>";
        echo "</td>";

        echo "<td>";
        echo "<button onclick=\"OpenWindow('archivo_importar.php?codbiometric=" . $row['codbiometric'] . "', 'frame_rejilla','98%','98%')\" 
        class='btn btn-warning left-margin btn-xs'>";
        echo "<span class='glyphicon glyphicon-edit'></span> Importar Datos";
        echo "</button>";
        echo "</td>";

        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<td>";
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar('" . $row['codbiometric'] . "');\"></span>";
            echo "</a>";
            echo "</td>";        
        } 
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
}

// if there are no user
else{
    echo "<div>"._("No se encontraron registros"). " </div>";
    }
?>
