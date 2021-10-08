<?php
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   
require_once __DIR__ .'/../common/sectores.php';   //Array con lista de los sectores del sistema
require_once __DIR__ .'/../common/verificopermisos.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('usuarios');

$obj->Select('codusuarios, nombre, apellido, telefono, celular, email, estado, tratamiento, huella');


$search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±");
$replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ");

// include header file
$page_title = "Listado de Usuarios";
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
                }elseif($key=='celular' or $key=='telefono'){
                    $obj->Where('celular', $value, 'LIKE', '', '(');
                    $obj->Where('telefono', $value, 'LIKE', 'or',  ')');
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
                if($key=='celular' or $key=='telefono'){
                    $obj->Where('celular', $value, 'LIKE', '', '(');
                    $obj->Where('telefono', $value, 'LIKE', 'or',  ')');
                }elseif($key=='nombre' or $key=='apellido'){
                    $obj->Where('nombre', $value, 'LIKE', '', '(');
                    $obj->Where('apellido', $value, 'LIKE', 'or',  ')');
                }elseif(strlen(trim($value)) >0 and $value!='0'){ 
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

if($UserTpo!=100){
    $obj->Where('tratamiento', '100', '!=');
}

// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : $page; // page is the current page, if there's nothing set, default is page 1

$records_per_page = 10; // set records or rows of data per page
$from_record_num = ($records_per_page * $page) - $records_per_page; // calculate for the query limit clause


$data=array_envia($data); 
$obj->Orden("estado" , "ASC");
$obj->Orden("apellido" , "DESC");
$obj->Limit($from_record_num, $records_per_page);

$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

// include pagination file
include_once 'pagination.php';

$eliminar=verificopermisos('Usuarios', 'eliminar', $UserID);

// check if more than 0 record found
if($total_rows>=0){
?>
    <table class='table table-hover table-responsive table-bordered table-condensed' id="rejilla">
    <thead class="bg-primary">
        <tr>
            <th colspan="2" class="fit"><?php echo _("ACCIÓN");?></th>
            <th><?php echo _("Nombre");?></th>
            <th><?php echo _("Apellido");?></th>
            <th><?php echo _("Teléfono");?></th>            
            <th><?php echo _("Email");?></th>            
            <th><?php echo _("Tipo");?></th>
            <th class="fit"></th>
        </tr>
    </thead>
    <tbody>
    <input type="hidden" name="numfilas" id="numfilas" value="<?php echo $total_rows; ?>">

<?php
foreach($rows as $row){
    if ($row['estado'] ==1) { $fondolinea="danger"; } else { $fondolinea=""; }
    
        echo "<tr class=\"triggerRejilla ".$fondolinea."\" data-codusuarios=\"".$row['codusuarios']."\" >";
        echo "<td>";
        // edit user button
        echo "<button onclick=\"OpenWindow('edit.php?codusuarios=" . $row['codusuarios'] . "', 'frame_rejilla','98%','98%')\" 
        class='btn btn-warning left-margin btn-xs'>";
        echo "<span class='glyphicon glyphicon-edit'></span> Editar";
        echo "</button>";
        echo "</td>";

        echo "<td>";
        // Ver marcas del reloj
        if($row['huella']==1){
        echo "<button onclick=\"OpenWindow('calendario.php?codusuarios=" . $row['codusuarios'] . "', 'frame_rejilla','98%','98%')\" 
        class='btn btn-warning left-margin btn-xs'>";
        echo "<span class='glyphicon glyphicon-edit'></span> Marca Horas";
        echo "</button>";
        }
        echo "</td>";

        echo "<td>$row[nombre]</td>";
        echo "<td>".str_replace($search, $replace, $row['apellido'])."</td>";
        echo "<td>".$row['telefono']." - ".$row['celular']." </td>";
        echo "<td>$row[email]</td>";

        echo "<td>".@$tipo[$row['tratamiento']]."</td>";
        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<td>";
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar('" . $row['codusuarios'] . "');\"></span>";
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
