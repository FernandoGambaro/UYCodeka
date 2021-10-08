<?php
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('facturas');
$obj->Select('facturas.codcliente,facturas.fecha, facturas.tipo, facturas.codformapago, facturas.enviada, facturas.emitida, facturas.codfactura, clientes.nombre,
 clientes.apellido, clientes.empresa, facturas.totalfactura,facturas.estado,
facturas.moneda ');
$obj->Join('codcliente', 'clientes', 'INNER', 'facturas', 'codcliente' );



$search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±");
$replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ");

// include header file
$page_title = "Listado articulos";
include_once "header-rejilla.php";

// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1
$records_per_page = 15; // set records or rows of data per page
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
            if(strlen(trim($value)) >0 and $key=='codcliente'){ 
                $obj->Where('codcliente', $value, '=', 'or', '', 'clientes');
            } elseif(strlen(trim($value)) >0 and $key=='stock') {          
                $obj->Where('stock', $value, '>=');  
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
                if(strlen(trim($value)) >0 and $key=='codcliente'){ 
                    $obj->Where('codcliente', $value, '=', 'or', '', 'clientes');
                } elseif(strlen(trim($value)) >0 and $key=='stock') {          
                        $obj->Where('stock', $value, '>='); 
                        $obj->Orden('articulos.stock', 'ASC');    
                    
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

$obj->Where('borrado', '0', '', '', '', 'facturas');
$obj->Orden('facturas.codfactura', 'DESC');    

$obj->Orden('facturas.fecha', 'DESC');    

$obj->Limit($from_record_num, $records_per_page);

//print_r($obj);

$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

// include pagination file
include_once 'pagination.php';

/*Verifico los permisos del usuario logeado*/
$eliminar=verificopermisos('Ventas', 'eliminar', $UserID);

// check if more than 0 record found
if($total_rows>=0){

?>
<div class="table-responsive">
    <table class='table table-hover table-responsive table-bordered table-condensed ' id="rejilla">
    <thead class="bg-primary">
        <tr>
            <th class="fit" colspan="3"><?php echo _('ACCIÓN');?></th>
            <th class="fit"><?php echo _('Fecha');?></th>
            <th class="fit">&nbsp;<?php echo _('Nº Factura');?></th>
            <th><?php echo _('Cliente');?></th>
            <th class="fit"><?php echo _('Tipo');?></th>
            <th class="fit"><?php echo _('Moneda');?></th>
            <th class="fit"><?php echo _('Imp.');?></th>
            <th class="fit"><?php echo _('Estado');?></th>
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

$tipomon = array();

$objMon = new Consultas('monedas');
$objMon->Select();
$objMon->Where('orden', '3', '<');
$objMon->Where('borrado', '0');
$objMon->Orden('orden', 'ASC');
$selMon=$objMon->Ejecutar();
$filasMon=$selMon['datos'];


$xmon=1;
foreach($filasMon as $fila){
 $descripcion=explode(" ", $fila ["descripcion"]);
  $tipomon[$xmon]= $descripcion[0];
  $xmon++;
}
$tipof = array(0=>"Contado", 1=>"Credito", 2=>"Nota Credito");

foreach($rows as $row){
    if ($row['borrado'] ==1) { $fondolinea="danger"; } else { $fondolinea=""; }

        echo "<tr class=\"btn-inverse trigger ".$fondolinea."\" data-codfactura=\"".$row['codfactura']."\" >";
        echo "<td>";
        // Modificar datos del cliente
        if ($row["emitida"] == 0) {
            $accion=1;
        }else{
            $accion=0;
        }
        if ( $modificar=="true") {
            echo "<button onclick=\"OpenWindow('edit.php?codfactura=" . $row['codfactura'] . "&accion=".$accion."', 'frame_rejilla','98%','98%',true, true,true, 'form')\" 
            class='btn btn-warning left-margin btn-xs'>";
            echo "<span class='glyphicon glyphicon-edit'></span> Editar";
            echo "</button>";
        }  
        echo "</td>"; 
        echo "<td>";
        
        if($row['emitida'] == 1){
            echo '<button class="btn btn-dark left-margin btn-xs" onClick="imprimir('.$row['codfactura']. ','. $row["emitida"].', )" ><i class="glyphicon glyphicon-print" aria-hidden="true"></i>
            Re-Imprimir</button>';
            echo "</td>";      
        }else{
            echo '<button class="btn btn-danger left-margin btn-xs" onClick="imprimir('.$row['codfactura']. ','. $row["emitida"].')" ><i class="glyphicon glyphicon-print" aria-hidden="true"></i>
            Imprimir</button>';
            echo "</td>";      
        }
        echo "<td>";
        if ($row["enviada"] != 1) {    
            echo "<button onclick=\"enviar_factura('". $row['codfactura'] . "', '0')\" class='btn btn-dark left-margin btn-xs' > ";
            echo "<i class='fa fa-share' aria-hidden='true'></i>&nbsp;Enviar&nbsp;&nbsp;&nbsp;";
            echo "</button>";
        } else {
            echo "<button onclick=\"enviar_factura('". $row['codfactura'] . "', '1')\"  class='btn btn-secondary left-margin btn-xs' >";
            echo "<i class='fa fa-envelope' aria-hidden='true'></i>&nbsp;Enviado";
            echo "</button>";
        }

        echo "</td>";
        echo "<td class=\"fit\">";
        echo implota($row['fecha']);
        echo "</td>";
        echo "<td>". $row['codfactura']. "</td>";
        echo "<td>";
        if(strlen($row['empresa'])>0){
            echo $row['empresa']. ' - ';
        }
        echo $row['nombre']. ' '.$row['apellido']. "</td>";
        echo "<td>".$tipof[$row['tipo']];
        echo "</td>";
        echo "<td>";
        echo $tipomon[$row['moneda']];
        echo "</td>";

        echo "<td>".number_format($row['totalfactura'],2,",","."). "</td>";

        echo "<td>";

        if ($row["emitida"] == 0) {
            echo '<button type="button" class="btn btn-danger">Sin&nbsp;emitir&nbsp;&nbsp;</button>';
        } else {
            if ($row["estado"] == 1 and $row["tipo"] != 0){
                echo  '<button type="button" class="btn btn-warning">Por&nbsp;cobrar</button>';
            } elseif ($row["estado"] == 2){
                echo  '<button type="button" class="btn btn-success">&nbsp;Cobrada&nbsp;&nbsp;&nbsp;</button>';
            } else { 
                echo "&nbsp;Parcial&nbsp;";
            }
        }


        echo "</td>";
        
        // delete button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<td>";
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar('" . $row['codfactura'] ."','" . $row['codcliente'] ."','facturas', '".$row["emitida"] ."');\"></span>";
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
