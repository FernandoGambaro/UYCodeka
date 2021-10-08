<?php
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('proveedores');
$obj->Select();


$search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±");
$replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ");

// include header file
$page_title = "Listado proveedores";
include_once "header-rejilla.php";

// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1
$records_per_page = 10; // set records or rows of data per page
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
            } else{          
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
                } else{          
                    if(strlen(trim($value)) >0){ 
                        $obj->Where($key, $value, 'LIKE');    
                    }   
                }
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
/*Verifico los permisos del usuario logeado*/
$eliminar=verificopermisos('Clientes', 'eliminar', $UserID);


if($total_rows>=0){
?>
<div class="table-responsive">
    <table class='table table-hover table-responsive table-bordered table-condensed ' id="rejilla">
    <thead class="bg-primary">
        <tr>
            <th colspan="2" class="fit"><?php echo _('ACCIÓN');?></th>
            <th class="fit">&nbsp;<?php echo _('NOMBRE/RAZÓN SOCIAL');?></th>
            <th><?php echo _('DOC/RUT');?></th>
            <th><?php echo _('EMAIL');?></th>
            <th><?php echo _('WEB');?></th>
            <th><?php echo _('TELÉFONO');?></th>
            <?php         // delete user button
		        if ( $UserTpo == 100 or $eliminar=="true") {
            ?>
            <th class="fit"></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
    <input type="hidden" name="numfilas" id="numfilas" value="<?php echo $total_rows; ?>">

<?php
$tipoCliente = array("Sin&nbsp;definir", "Común","Abonado&nbsp;A", "Abonado&nbsp;B");
$tiponif = array("", "RUT","CI", "Pasaporte");
foreach($rows as $row){

        echo "<tr class=\"btn-inverse trigger\" data-proveedor=\"".$row['codproveedor']."\" >";
        echo "<td>";
        // Modificar datos del cliente
        if ( $modificar=="true" or $UserTpo==100) {
            echo "<button onclick=\"OpenWindow('edit.php?codproveedor=" . $row['codproveedor'] . "', 'frame_rejilla','98%','98%')\" 
            class='btn btn-warning left-margin btn-xs'>";
            echo "<span class='glyphicon glyphicon-edit'></span> Editar";
            echo "</button>";
        }  
        echo "</td>"; 
        echo "<td>";
        if($row['direccion']!="" ) {
            echo "<button onclick=\"OpenWindow('vermapa.php?codproveedor=" . $row['codproveedor'] . "', 'frame_rejilla','98%','400')\" 
            class='btn btn-secondary left-margin btn-xs'>";            
            echo '<span class="fa-stack"><i class="fa fa-map-marker" aria-hidden="true"></i></span>';
            echo "</button>"; 
        }         
        echo "</td>";        
        echo "<td class=\"fit\">";
            echo $row['nombre'];
        echo "</td>";
        echo "<td>".$row['nif']. "</td>";
        echo "<td>".$row['email']. "</td>";
        echo "<td>".$row['web']. "</td>";
        echo "<td>".$row['telefono'];
        if ($row['movil']!='') echo " / ". $row['movil'];
        echo "</td>";
        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<td  class=\"fit\">";
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar('" . $row['codproveedor'] . "');\"></span>";
            echo "</a>";
            echo "</td>";        
        } 
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
