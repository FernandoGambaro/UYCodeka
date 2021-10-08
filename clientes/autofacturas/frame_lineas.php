<?php
require_once __DIR__ .'/../../common/funcionesvarias.php';   
require_once __DIR__ .'/../../common/fechas.php';   

// include header file
$page_title = "Listado elementos de la factura";
include_once "header-rejilla.php";


require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

$codautofacturatmp=isset($_GET['codautofacturatmp']) ? $_GET['codautofacturatmp'] : $_POST['codautofacturatmp'];
$total_importe=0;

if($_POST)
{
	$codcliente=isset($_GET['codcliente']) ? $_GET['codcliente'] : $_POST['codcliente'];

	//Verifico que no exista el articulo en la linea de la factura, si existe sumo cantidad
	$obj = new Consultas('autofactulineatmp');
	$obj->Select('codigo, cantidad' );
	$obj->Where('codautofactura', $codautofacturatmp, '='); 
	$obj->Where('codigo', $_POST['codarticulo']);   

	$paciente = $obj->Ejecutar();
	$total_rows=$paciente["numfilas"];
	$rows = $paciente["datos"][0];
	if($paciente["numfilas"]>0){
		$cantidadAnterior=$rows['cantidad'];

		$nombres = array();
		$valores = array();
		$nombres[] = "cantidad";
		$valores[] = $_POST["cantidad"];
		$nombres[] = "borrado";
		$valores[] = '0';
		$obj = new Consultas('autofactulineatmp');
		$obj->Update($nombres, $valores );
		$obj->Where('codautofactura', $codautofacturatmp, '='); 
		$obj->Where('codigo', $_POST['codarticulo']);   
	
		$paciente = $obj->Ejecutar();
		$paciente['consulta'];
	
	}else{

		$nombres = array();
		$valores = array();

		$nombres[] = "codautofactura";
		$valores[] = $codautofacturatmp;
		$nombres[] = "codfamilia";
		$valores[] = $_POST["codfamilia"];
		$nombres[] = "codigo";
		$valores[] = $_POST["codarticulo"];
		$nombres[] = "detalles";
		$valores[] = $_POST["detalles"];
		$nombres[] = "cantidad";
		$valores[] = $_POST["cantidad"];
		$nombres[] = "precio";
		$valores[] = $_POST["precio"];
		$nombres[] = "moneda";
		$valores[] = $_POST["moneda"];
		$nombres[] = "importe";
		$valores[] = $_POST["importe"];
		$nombres[] = "dcto";
		$valores[] = $_POST["descuento"];

		$objTmp = new Consultas('autofactulineatmp');
		$objTmp->Insert($nombres, $valores);
		$resultado=$objTmp->Ejecutar();
		//echo $resultado['consulta'];
	}
}

$total_rows=0;
if(strlen($codautofacturatmp)>0){
$obj = new Consultas('autofactulineatmp');
$obj->Select('autofactulineatmp.numlinea, autofactulineatmp.codautofactura, autofactulineatmp.codfamilia, autofactulineatmp.cantidad, autofactulineatmp.precio,
 autofactulineatmp.importe, autofactulineatmp.dcto, autofactulineatmp.codigo,
 articulos.codarticulo, articulos.descripcion, familias.nombre as nombrefamilia ');
$obj->Join('codigo', 'articulos', 'INNER', 'autofactulineatmp', 'codarticulo' );
$obj->Join('codfamilia', 'articulos', 'INNER', 'familias', 'codfamilia' );

$obj->Where('codautofactura', $codautofacturatmp);    
$obj->Where('borrado', '0', '', '', '', 'autofactulineatmp');    

$paciente = $obj->Ejecutar();

$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

/*Verifico los permisos del usuario logeado*/
$eliminar=verificopermisos('compras', 'eliminar', $UserID);
}
// check if more than 0 record found

?>
<div class="table-responsive">
    <table class='table table-hover table-responsive table-bordered table-condensed ' id="rejilla">
    <thead class="bg-primary">
        <tr>
            <th><?php echo _('Código');?></th>
            <th>&nbsp;<?php echo _('Descripción');?></th>
            <th><?php echo _('Cantidad');?></th>
            <th><?php echo _('Precio');?></th>
            <th><?php echo _('Dcto %');?></th>
            <th class="fit"><?php echo _('Importe');?></th>
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
if($total_rows>0){

foreach($rows as $row){
    if ($row['borrado'] ==1) { $fondolinea="danger"; } else { $fondolinea=""; }

        echo "<tr class=\"btn-inverse trigger ".$fondolinea."\" data-codautofactura=\"".$row['codautofactura']."\" >";

        echo "<td class=\"fit\">";
        echo $row['codarticulo'];
        echo "</td>";
        echo "<td>".$row['descripcion']. "</td>";
        echo "<td>".$row['cantidad'];
        echo "</td>";
        echo "<td>".$row['precio']."</td>";
        echo "<td>". $row['dcto']. "</td>";

		$importe=(float)$row["importe"];
		$total_importe=(float)$total_importe+$importe;
        echo "<td>".number_format($importe,2,",","."). "</td>";

        
        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<td>";
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar_linea('".$codautofacturatmp."','". $row['numlinea']."');\"></span>";
            echo "</a>";
            echo "</td>";        
        } 
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
}
?>

<script type="text/javascript">
parent.pon_baseimponible(Number(<?php echo $importe;?>));
</script>
