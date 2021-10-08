<?php
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('feedback');
$obj->Select('formularios.tipo, feedback.codfeedback, feedback.fecha, feedback.fechaproxima, feedback.fechafeedback,feedback.colaborador,
 feedback.codusuarios as usuario, feedback.codformulario, feedback.devolucion');
$obj->Join('colaborador', 'usuarios', 'INNER', 'feedback', 'codusuarios');
$obj->Join('codformulario', 'feedback', 'INNER', 'formularios', 'codformulario');

//$codcliente=isset($_REQUEST["codcliente"]) ? $_REQUEST["codcliente"] : '';

// include header file
$page_title = "Listado horas realizadas";

include_once "header-rejilla.php";


$alldata="";
$data=array();
$alldata=isset($_GET['alldata']) ? $_GET['alldata'] : null ;
$alldata=array_recibe($alldata);

// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1
$records_per_page = 8; // set records or rows of data per page
$from_record_num = ($records_per_page * $page) - $records_per_page; // calculate for the query limit clause

$value='';
if(is_array($alldata) && !empty($alldata)){
	foreach ($alldata as $key => $value)
	{
		if (!empty($_GET[$key])) {
			$$key=$_GET[$key];
		} else {
		$temp = is_array($value) ? $value : trim($value);
	    $$key = $temp;
        $data[$$key]=$temp;
            if(strlen(trim($value)) >0 and $key=='fecha'){ 
                $obj->Where('fecha', explota($value)." 00:00:00", '=');    
            }elseif(strlen(trim($value)) >0 and $key=='codusuarios'){ 
                $obj->Where('codusuarios', $value, '=', '' ,'', 'feedback');    
            }elseif(strlen(trim($value)) >0 and $key=='formulario'){ 
                $obj->Where('codformulario', $value, '=', '' ,'', 'feedback' );    
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
                if(strlen(trim($value)) >0 and $key=='fecha'){ 
                    $obj->Where('fecha', explota($value)." 00:00:00", '=');    
                }elseif(strlen(trim($value)) >0 and $key=='codusuarios'){ 
                    $obj->Where('codusuarios', $value, '=', '' ,'', 'feedback');    
                }elseif(strlen(trim($value)) >0 and $key=='formulario'){ 
                    $obj->Where('codformulario', $value, '=', '' ,'', 'feedback' );    
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

$obj->Group('feedback.fecha');
$obj->Group('feedback.colaborador');
$obj->Group('feedback.codformulario');
$obj->Orden('fecha' , 'DESC');

$obj->Limit($from_record_num, $records_per_page);

//var_dump($obj);
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
            <th class="fit"><?php echo 'ACCIONES';?></th>
            <th class="fit"></th>
            <th class="fit"><?php echo 'Fecha';?></th>
            <th class="fit"><?php echo 'Próxima Evaluación';?></th>
            <?php 
            $modificarusuarios=verificopermisos('usuarios', 'modificar', $UserID);
            if($modificarusuarios=="true") { ?>
            <th><?php echo 'Evaluador';?></th>
            <?php } ?>

            <th><?php echo 'Colaborador';?></th>
            <th><?php echo 'Formulario';?></th>
            <th class="fit"><?php echo 'Devolución';?></th>
            <th class="fit"></th>
        </tr>
    </thead>
    <tbody>
    <input type="hidden" name="numfilas" id="numfilas" value="<?php echo $total_rows; ?>">
    <input type="hidden" name="seleccionados" id="seleccionados" value="">

<?php
/*Verifico los permisos del usuario logeado*/
$eliminar=verificopermisos('ControlHoras', 'eliminar', $UserID);


foreach($rows as $row){

        echo "<tr class=\"btn-inverse trigger\" data-codfeedback=\"".$row['codfeedback']."\" 
        data-colaborador=\"".$row['colaborador']."\" data-codfeedback=\"".$row['tipo']."\" 
        data-fecha=\"".$row['fecha']."\">";

        echo "<td>";
        if ( $modificar=="true" and $fechaaux>=$fechadehoy) { 
            if( $row['tipo']==0) {
        // edit user button
        echo "<button onclick=\"OpenWindow('feedback.php?fecha=". $row['fecha'] ."&colaborador=". $row['colaborador'] ."', '#frame_rejilla','99%','99%')\" class='btn btn-secondary left-margin btn-xs'>";
        echo "<span class='glyphicon glyphicon-edit'></span>Feedback";
        echo "</button>";
            }else{
        // edit user button
        echo "<button onclick=\"OpenWindow('modificar_devolucion.php?codfeedback=". $row['codfeedback'] ."', '#frame_rejilla','99%','99%')\" 
        class='btn btn-secondary left-margin btn-xs'>";
        echo "<span class='glyphicon glyphicon-edit'></span>Devolución";
        echo "</button>";
            }
        }
        echo "</td>";


        echo "<td>";
        // edit user button
        if($row['devolucion']==0 and $row['tipo']==0 ) {
        ?>
        <label>
        <input class="checkbox1" type="checkbox" name="SELECT[<?php echo $row['codfeedback']?>]" value="<?php echo $row['codfeedback'];?>" >
        <span></span></label>
        <?php }
        echo "</td>";

        echo "<td class=\"fit\">";
        echo implota($row['fecha']);
        $fechaaux=strtotime ( '+1 day' , strtotime ( $row['fecha'] ) );     
        
        echo "</td>";
        echo "<td>". implota($row['fechaproxima']). "</td>";
        echo "<td>";

        if($modificarusuarios=="true") { 
            $objclien = new Consultas('usuarios');

            $objclien->Select();
            $objclien->Where('codusuarios', $row['usuario']);
            $clien = $objclien->Ejecutar();
            $total_clientes=$clien["numfilas"];
            
            if($total_clientes>0){
                $rowclien=$clien["datos"][0];
                echo substr($rowclien['nombre'], 0, 20)." ". substr($rowclien['apellido'], 0, 20);
            }else{
                echo "";
            }
        }else{
            echo "";
        }
//        .$row['codproyectos'].
        
        echo "</td>";
        echo "<td>";
        $objclien = new Consultas('usuarios');

        $objclien->Select();
        $objclien->Where('codusuarios', $row['colaborador']);
        $clien = $objclien->Ejecutar();
        $total_clientes=$clien["numfilas"];
        
        if($total_clientes>0){
            $rowclien=$clien["datos"][0];
            echo substr($rowclien['nombre'], 0, 20)." ". substr($rowclien['apellido'], 0, 20);
        }else{
            echo "";
        }
        echo "</td>";
        echo "<td>";
        if(strlen($row['codformulario'])>0){
            $objformularios = new Consultas('formularios');
            $objformularios->Select();
            $objformularios->Where('codformulario', $row['codformulario']);    
            $objformularios->Orden("descripcion" , "ASC");
            $formularios = $objformularios->Ejecutar();
            $rowsformularios = $formularios["datos"][0];
        }
        echo $tipo[$row['tipo']]." - ".$rowsformularios['descripcion'];

        echo "</td>";
        echo "<td class=\"fit\">".$row['fechafeedback']."</td>";

        echo "<td>";
        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            if( $row['tipo']==0) {
                echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar_feedback('".$row['fecha']."','".$row['colaborador']."');\"></span>";
            }else{
                echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar_devolucion('".$row['fecha']."','".$row['codfeedback']."');\"></span>";
            }
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
    echo "<div>".'No se encontraron registros.'." </div>";
    }
?>
