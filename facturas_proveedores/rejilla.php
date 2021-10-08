<?php
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('facturasp');
$obj->Select('articulos.descripcion, facturasp.codproveedor,facturasp.fecha, facturasp.codfactura, proveedores.nombre, facturasp.totalfactura,facturasp.estado,
facturasp.moneda,facturasp.tipo ');
$obj->Join('codproveedor', 'proveedores', 'INNER', 'facturasp', 'codproveedor' );
$obj->Join('codfactura', 'facturasp', 'INNER', 'factulineap', 'codfactura');
$obj->Join('codarticulo', 'factulineap', 'INNER', 'articulos', 'codigo');
$obj->Select();


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
            if(strlen(trim($value)) >0 and $key=='codproveedor'){ 
                $obj->Where('codproveedor1', $value, '', '',  '(');
                $obj->Where('codproveedor2', $value, '', 'or',  ')');
            }elseif(strlen(trim($value)) >0 and $key=='codfamilia'){                 
                $obj->Where($key, $value, '=', '', '', 'proveedores');
            } elseif(strlen(trim($value)) >0 and $key=='stock') {          
                $obj->Where('stock', $value, '>=');  
 
            } elseif(strlen(trim($value)) >0 and $key=='codigobarras') {          
                $obj->Where('codigobarras', $value, 'LIKE', '',  '(');   
                $obj->Where('referencia', $value, 'LIKE', 'or', ')');   
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
                if(strlen(trim($value)) >0 and $key=='codproveedor'){ 
                    $obj->Where('codproveedor', $value, '=', '', '', 'proveedores');
                    }elseif(strlen(trim($value)) >0 and $key=='codfamilia'){ 
                    $obj->Where($key, $value, '=', '', '', 'proveedores');
                } elseif(strlen(trim($value)) >0 and $key=='stock') {          
                        $obj->Where('stock', $value, '>='); 
                        $obj->Orden('articulos.stock', 'ASC');    
                    
                } elseif(strlen(trim($value)) >0 and $key=='codigobarras') {          
                    $obj->Where('codigobarras', $value, 'LIKE', '',  '(');   
                    $obj->Where('referencia', $value, 'LIKE', 'or', ')');   
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

$obj->Where('borrado', '0', '', '', '', 'facturasp');

$obj->Orden('facturasp.fecha', 'DESC');    
$obj->Group('facturasp.codfactura');
$obj->Limit($from_record_num, $records_per_page);

//print_r($obj);

$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

// include pagination file
include_once 'pagination.php';

/*Verifico los permisos del usuario logeado*/
$eliminar=verificopermisos('Compras', 'eliminar', $UserID);

// check if more than 0 record found
if($total_rows>=0){

?>
<div class="table-responsive">
    <table class='table table-hover table-responsive table-bordered table-condensed ' id="rejilla">
    <thead class="bg-primary">
        <tr>
            <th class="fit"><?php echo _('ACCIÓN');?></th>
            <th><?php echo _('Fecha');?></th>
            <th>&nbsp;<?php echo _('Nº Factura');?></th>
            <th><?php echo _('Proveedor');?></th>
            <th><?php echo _('Tipo');?></th>
            <th><?php echo _('Moneda');?></th>
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
        if ( $modificar=="true") {
            echo "<button onclick=\"OpenWindow('edit.php?codfactura=" . $row['codfactura'] . "&codproveedor=".$row['codproveedor']."', 'frame_rejilla','98%','98%',true, true,true, )\" 
            class='btn btn-warning left-margin btn-xs'>";
            echo "<span class='glyphicon glyphicon-edit'></span> Editar";
            echo "</button>";
        }  
        echo "</td>"; 

        echo "<td class=\"fit\">";
        echo implota($row['fecha']);
        echo "</td>";
        echo "<td>". $row['codfactura']. "</td>";
        echo "<td>".$row['nombre']. "</td>";
        echo "<td>".$tipof[$row['tipo']];
        echo "</td>";
        echo "<td>";
        echo $tipomon[$row['moneda']];
        echo "</td>";

        echo "<td>".number_format($row['totalfactura'],2,",","."). "</td>";

        echo "<td>";
        if ($row["estado"] == 1 and $row["tipo"] >= 1 ) { echo "Sin&nbsp;pagar"; } else { echo "Pagada"; } 
        echo "</td>";
        
        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<td>";
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar('" . $row['codfactura'] ."','" .$row['codproveedor']. "','facturasp');\"></span>";
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
