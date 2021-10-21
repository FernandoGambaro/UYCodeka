<?php
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


if($_POST)
{
    $nombres = array();
    $valores = array();

    foreach ($_REQUEST as $key => $item)
    {
        if(strlen($item)>0){
            if(strpos($item, '/')>0){
                $valores[] = explota($item);
            }else {
                $valores[] = $item;
            }
            if($key=='codrecibo'){
                $codrecibo=$item;
            }
            if($key=='codfactura'){
                $codfactura=$item;
            }
            $nombres[] = $key;
        }
    }
    if(strlen($codfactura)>0){
		$objTmp = new Consultas('recibosfacturatmp');
        $objTmp->Insert($nombres, $valores);
        $resultado=$objTmp->Ejecutar();
        
        $nombres = array();
        $valores = array();
        $nombres[] = 'estado';
        $valores[ ]= '-1';
        $objTmp = new Consultas('facturas');
        $objTmp->Update($nombres, $valores);
        $objTmp->Where('codfactura', $codfactura);
        $resultado=$objTmp->Ejecutar();
        //echo '<br>'.$resultado['consulta'];

    }
}

$obj = new Consultas('recibosfacturatmp');
$obj->Select('recibosfacturatmp.codrecibo,recibosfacturatmp.codfactura as codfactura, facturas.fecha as fecha ,
facturas.tipo as tipo,facturas.moneda,facturas.estado,facturas.totalfactura');
$obj->Join('codfactura', 'facturas', 'INNER', 'recibosfacturatmp', 'codfactura' );

// include header file
$page_title = "Listado facturas en el recibo";
include_once "header-rejilla.php";

$codrecibo=isset($_GET['codrecibo']) ? $_GET['codrecibo'] : $_POST['codrecibo'];

$obj->Where('borrado', '0', '', '', '', 'facturas');
$obj->Where('tipo', '1', '', '', '', 'facturas');
$obj->Where('codrecibo', $codrecibo, '', '', '', 'recibosfacturatmp');

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
            <th class="fit"></th>
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

$total_importe=0;

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

        $importe=(float)$row["totalfactura"];
		$total_importe=(float)$total_importe+$importe;
        echo "<td>".number_format($row['totalfactura'],2,",","."). "</td>";

        echo "<td>";
            echo  '<button type="button" class="btn btn-warning">';
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar_linea('" . $row['codfactura'] ."','". $codrecibo . "','recibosfacturatmp');\"></span></button>";
        echo "</td>";
        
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
}

else{
    echo "<div>". _('No se encontraron registros.'). "</div>";
    }
?>
<script type="text/javascript">
console.log(Number(<?php echo $total_importe;?>));
parent.pon_total(Number(<?php echo $total_importe;?>));
</script>