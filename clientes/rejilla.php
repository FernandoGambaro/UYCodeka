<?php
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('clientes');
$obj->Select();


$search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±");
$replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ");

// include header file
$page_title = "Listado clientes";
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
            } elseif(strlen(trim($value)) >0 and $key=='nombre') {          
                if(strlen(trim($value)) >0){ 
                    $obj->Where('nombre', $value, 'LIKE', '', '(');    
                    $obj->Where('apellido', $value, 'LIKE', 'or', ')');    
                }   
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
                } elseif(strlen(trim($value)) >0 and $key=='nombre') {          
                    if(strlen(trim($value)) >0){ 
                        $obj->Where('nombre', $value, 'LIKE', '', '(');    
                        $obj->Where('apellido', $value, 'LIKE', 'or', ')');    
                    }   
                } else{          
                    if(strlen(trim($value)) >0){ 
                        $obj->Where($key, $value, 'LIKE');    
                    }   
                }
            } elseif($key=='page' and $value!=''){
                $page=$value;
            }         
        }

}

$data=array_envia($data); 

$obj->Orden('borrado', 'ASC');    
$obj->Orden("service" , "DESC");
$obj->Orden("empresa" , "ASC");
$obj->Limit($from_record_num, $records_per_page);

$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

// include pagination file
include_once 'pagination.php';

/*Verifico los permisos del usuario logeado*/
$eliminar=verificopermisos('Clientes', 'eliminar', $UserID);

// check if more than 0 record found
if($total_rows>=0){

?>
<div class="table-responsive">
    <table class='table table-hover table-responsive table-bordered table-condensed ' id="rejilla">
    <thead class="bg-primary">
        <tr>
            <th colspan="2" class="fit"><?php echo _('ACCIÓN');?></th>
            <?php
            $equipos=verificopermisos('EquiposCliente', 'leer', $UserID);
            $respaldos=verificopermisos('RespaldosCliente', 'leer', $UserID);
            $proyectos=verificopermisos('Proyectoscliente', 'leer', $UserID);            

            $controlhoras=verificopermisos('ControlHoras', 'leer', $UserID);
            
             if ( $controlhoras=="true") { ?>
            <th class="fit"><?php echo _('HIST.');?></th>
            <?php } else { ?>
            <th class="fit">&nbsp;</th>							
            <?php } ?>
            <th class="fit">&nbsp;<?php echo _('NOMBRE/RAZÓN SOCIAL');?></th>
            <th><?php echo _('DOC/RUT');?></th>
            <th><?php echo _('EMAIL');?></th>
            <th><?php echo _('TELÉFONO');?></th>
            <th class="fit"><?php echo _('TIPO');?></th>
            <th class="fit"><?php echo _('Hr.');?></th>
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
    if ($row['borrado'] ==1) { $fondolinea="danger"; } else { $fondolinea=""; }

        echo "<tr class=\"btn-inverse trigger ".$fondolinea."\" data-codcliente=\"".$row['codcliente']."\" >";
        echo "<td>";
        // Modificar datos del cliente
        if ( $modificar=="true") {
            echo "<button onclick=\"OpenWindow('edit.php?codcliente=" . $row['codcliente'] . "', 'frame_rejilla','98%','98%')\" 
            class='btn btn-warning left-margin btn-xs'>";
            echo "<span class='glyphicon glyphicon-edit'></span> Editar";
            echo "</button>";
        }  
        echo "</td>"; 
        echo "<td>";
        if($row['direccion']!="" ) {
            echo "<button onclick=\"OpenWindow('vermapa.php?codcliente=" . $row['codcliente'] . "', 'frame_rejilla','98%','400')\" 
            class='btn btn-secondary left-margin btn-xs'>";            
            echo '<span class="fa-stack"><i class="fa fa-map-marker" aria-hidden="true"></i></span>';
            echo "</button>"; 
        }         
        echo "</td>";        
        echo "<td>";
        // Listar service del cliente
        if ( $controlhoras=="true") { 
            echo "<button onclick=\"OpenWindow('../controlhoras/index.php?codcliente=" . $row['codcliente'] . "', 'frame_rejilla','98%','98%',Close = true, Scroll = true, CloseButton = true)\" 
        class='btn btn-primary left-margin btn-xs'>";
        echo '<span class="fa-stack"><i class="fa fa-server" aria-hidden="true"></i></span>';
        echo "</button>"; 
        }  else {
            echo "";
        }
        echo "</td>";
        echo "<td class=\"fit\">";
        $Descripcion=$row['nombre']." ".$row['apellido'];
        if($row['empresa']!='') {
            $Descripcion=$row['empresa'];
        }
        echo $Descripcion;
        echo "</td>";
        echo "<td>". $tiponif[$row['tiponif']]. '&nbsp;'. $row['nif']. "</td>";
        echo "<td>".$row['email']. "</td>";
        echo "<td>".$row['telefono'];
        if ($row['movil']!='') echo " / ". $row['movil'];
        echo "</td>";
        echo "<td>".$tipoCliente[$row['service']]. "</td>";
        echo "<td>";
        if ($row['horas']<>0 and $row['service']>1){ echo $row['horas']."hr";}
        echo "</td>";
        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<td>";
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar('" . $row['codcliente'] . "','clientes');\"></span>";
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
