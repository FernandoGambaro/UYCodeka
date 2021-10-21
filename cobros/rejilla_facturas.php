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
                $obj->Where($key, $value, '=', '', '', 'clientes');
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
                    $obj->Where($key, $value, '=', '', '', 'clientes');
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
$obj->Where('estado', '1', '=', 'and', '(', 'facturas');
$obj->Where('estado', '4', '=', 'or', ')', 'facturas');


$obj->Orden('facturas.fecha', 'DESC');    

//print_r($obj);

$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

// include pagination file

/*Verifico los permisos del usuario logeado*/
$eliminar=verificopermisos('Ventas', 'eliminar', $UserID);

// check if more than 0 record found
if($total_rows>=0){

?>
<div class="table-responsive">
    <table class='table table-hover table-responsive table-bordered table-condensed ' id="rejilla">
    <thead class="bg-primary">
        <tr>
            <th class="fit"><?php echo _('Fecha');?></th>
            <th class="fit">&nbsp;<?php echo _('Nº Factura');?></th>
            <th class="fit"><?php echo _('Tipo');?></th>
            <th class="fit"><?php echo _('Moneda');?></th>
            <th class="fit"><?php echo _('Imp.');?></th>
            <th class="fit"><?php echo _('Estado');?></th>

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

        echo "<td class=\"fit\">";
        echo implota($row['fecha']);
        echo "</td>";
        echo "<td>". $row['codfactura']. "</td>";
        echo "<td>".$tipof[$row['tipo']];
        echo "</td>";
        echo "<td>";
        echo $tipomon[$row['moneda']];
        echo "</td>";

        echo "<td>".number_format($row['totalfactura'],2,",","."). "</td>";

        echo "<td>";
            echo  '<button type="button" class="btn btn-warning" onclick="agregar_factura(\''. $row['codfactura'] .'\', \''. $row['totalfactura'] .'\');" >Agregar</button>';
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
