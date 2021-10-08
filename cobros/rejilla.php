<?php
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

$estado=isset($_POST['estado']) ? $_POST['estado'] : $_GET['estado'];

if($estado!=2){
$obj = new Consultas('facturas');
$obj->Select('facturas.codcliente,facturas.fecha, facturas.tipo, facturas.codformapago, facturas.enviada, facturas.emitida, facturas.codfactura, clientes.nombre,
 clientes.apellido, clientes.empresa, facturas.totalfactura,facturas.estado, formapago.dias, 
facturas.moneda ');
$obj->Join('codfactura', 'cobros', 'LEFT', 'facturas', 'codfactura' );
$obj->Join('codcliente', 'facturas', 'INNER', 'clientes', 'codcliente' );
$obj->Join('codformapago', 'formapago', 'INNER', 'formapago', 'codformapago' );
}elseif($estado==2){
$obj = new Consultas('recibos');
$obj->Select('recibos.codcliente, clientes.nombre, clientes.apellido, clientes.empresa, recibos.importe, recibos.estado, recibos.enviado, recibos.fecha,
 recibos.codrecibo, recibos.moneda, recibos.borrado');
$obj->Join('codcliente', 'clientes', 'INNER', 'recibos', 'codcliente');

}

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
            if(strlen(trim($value)) >0 and $key=='nombre'){
                $obj->Where('nombre', $value, 'LIKE', '', '(', 'clientes');
                $obj->Where('apellido', $value, 'LIKE', 'or', '', 'clientes');
                $obj->Where('empresa', $value, 'LIKE', 'or', ')', 'clientes');
            } elseif(strlen(trim($value)) >0 and $key=='estado') {
                if($estado!=2){
                    $obj->Where($key, '1', '<=', 'and', '(');
                    $obj->Where($key, '2', '>', 'or', ')');
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
                if(strlen(trim($value)) >0 and $key=='nombre'){ 
                    $obj->Where('nombre', $value, 'LIKE', '', '(', 'clientes');
                    $obj->Where('apellido', $value, 'LIKE', 'or', '', 'clientes');
                    $obj->Where('empresa', $value, 'LIKE', 'or', ')', 'clientes');
                } elseif(strlen(trim($value)) >0 and $key=='estado') { 
                    if($estado!=2){         
                        $obj->Where($key, '1', '<=', 'and', '(');   
                        $obj->Where($key, '2', '>', 'or', ')');       
                    }
                }else{          
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

if($estado!=2){
$obj->Where('borrado', '0', '', '', '', 'facturas');
$obj->Where('tipo', '1', '<=', '', '', 'facturas');
$obj->Where('totalfactura', '0', '!=', '', '', 'facturas');
$obj->Orden('facturas.codfactura', 'DESC');
$obj->Orden('facturas.fecha', 'DESC');
$obj->Group('codfactura');

}elseif($estado==2){
$obj->Where('borrado', '0', '', '', '', 'recibos');
$obj->Orden('codrecibo', 'DESC');
$obj->Orden('fecha', 'DESC');

}

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
        <?php 
        $colspan=2;
            if($estado==1){
                $colspan=1;
            }else{
                $colspan=2;
            }
        ?>
            <th class="fit" colspan="<?php echo $colspan;?>"><?php echo _('ACCIÓN');?></th>
            <th class="fit"><?php echo _('Fecha');?></th>
            <?php 
            if($estado!=2){
            ?>
                <th class="fit">&nbsp;<?php echo _('Nº Factura');?></th>
            <?php }else{ ?>
                <th class="fit">&nbsp;<?php echo _('Nº Recibo');?></th>
            <?php } ?>
            <th><?php echo _('Cliente');?></th>
            <th class="fit"><?php echo _('Moneda');?></th>
            <th class="fit"><?php echo _('Imp.');?></th>
            <th class="fit"><?php echo _('Estado');?></th>
            <?php if($estado!=2) { ?>
            <th class="fit"><?php echo _('Fecha Vto.');?></th>
            <?php } if($estado==2){ ?>
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
        if($estado!=2){
        echo "<tr class=\"btn-inverse trigger ".$fondolinea."\" data-codfactura=\"".$row['codfactura']."\" >";
        }else{
        echo "<tr class=\"btn-inverse trigger ".$fondolinea."\" data-codrecibo=\"".$row['codrecibo']."\" >";
        }
        echo "<td>";
if($estado!=2){
    echo "<button onclick=\"OpenWindow('create.php?codfactura=" . $row['codfactura'] ."', 'frame_rejilla','98%','98%',true, true,true, 'form')\" 
    class='btn btn-warning left-margin btn-xs'>";
    echo "<span class='glyphicon glyphicon-new-window'></span> Cobrar";

}else{
    if($row['enviado']==0){
        echo "<button onclick=\"OpenWindow('edit.php?codrecibo=" . $row['codrecibo'] ."', 'frame_rejilla','98%','98%',true, true,true, 'form')\" 
        class='btn btn-warning left-margin btn-xs'>";
        echo "<span class='glyphicon glyphicon-edit'></span> Editar";
    }else{
        echo "<button onclick=\"OpenWindow('edit.php?codrecibo=" . $row['codrecibo'] ."', 'frame_rejilla','98%','98%',true, true,true, 'form')\" 
        class='btn btn-warning left-margin btn-xs'>";
        echo "<span class='glyphicon glyphicon-edit'></span> Modificar";
    }
}
               
        
        echo "</button>";
        echo "</td>"; 
        if($estado==2){
            echo "<td>";
            if ($row['enviado'] != 1) {    
                echo "<button onclick=\"enviar_recibo('". $row['codrecibo'] . "', '0')\" class='btn btn-dark left-margin btn-xs' > ";
                echo "<i class='fa fa-share' aria-hidden='true'></i>&nbsp;Enviar&nbsp;&nbsp;&nbsp;";
                echo "</button>";
            } else {
                echo "<button onclick=\"enviar_recibo('". $row['codrecibo'] . "', '1')\"  class='btn btn-secondary left-margin btn-xs' >";
                echo "<i class='fa fa-envelope' aria-hidden='true'></i>&nbsp;Reenviar";
                echo "</button>";
            }
            echo "</td>";
        }
        echo "<td class=\"fit\">";
        echo implota($row['fecha']);
        echo "</td>";
        if($estado!=2){
            echo "<td>". $row['codfactura']. "</td>";
        }else{
            echo "<td>". $row['codrecibo']. "</td>";
        }
        echo "<td>";
        if(strlen($row['empresa'])>0){
            echo $row['empresa']. ' - ';
        }
        echo $row['nombre']. ' '.$row['apellido']. "</td>";
        echo "<td>";
        echo $tipomon[$row['moneda']];
        echo "</td>";
        if($estado!=2){
            echo "<td>".number_format($row['totalfactura'],2,",","."). "</td>";
            echo "<td>";
            if ($row["estado"] == 1 and $row["tipo"] != 0){
                echo  '<button class="btn btn-warning">Por&nbsp;cobrar</button>';
            } elseif ($row["estado"] == 2){
                echo  '<button type="button" class="btn btn-success">&nbsp;Cobrada&nbsp;&nbsp;&nbsp;</button>';
            } else { 
                echo  '<button type="button" class="btn btn-success">&nbsp;Sin&nbsp;cerrar&nbsp;&nbsp;</button>';
            }
            echo "</td>";
            echo "<td>";
            if($row["codformapago"]>0) { 
                if($row["dias"]!='') {
                    $fecha = date($row["fecha"]);
                    $nuevafecha = strtotime ( '+'.$row["dias"].' day' , strtotime ( $fecha ) ) ;
                    $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
                    echo implota($nuevafecha);							
                }
            }
            echo "</td>";  
        }else{
            echo "<td>".number_format($row['importe'],2,",","."). "</td>";            
            echo "<td>";
            if ($row["enviado"] == 1){
                echo  '<button class="btn btn-warning">Enviado</button>';
            }else{
                echo  '<button type="button" class="btn btn-success">&nbsp;Sin&nbsp;enviar&nbsp;&nbsp;</button>';
            }
            echo "</td>";            
        }  
        
                // delete button
		if ( ($UserTpo == 100 or $eliminar=="true") and $estado==2) {
            echo "<td>";
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar('" . $row['codrecibo'] ."','recibos', '".$row["estado"] ."');\"></span>";
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
