<?php
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   

// include header file
$page_title = "Listado elementos de la factura";
include_once "header-rejilla.php";


require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

$codfacturatmp=isset($_GET['codfacturatmp']) ? $_GET['codfacturatmp'] : $_POST['codfacturatmp'];


if($_POST)
{
	$codproveedor=isset($_GET['codproveedor']) ? $_GET['codproveedor'] : $_POST['codproveedor'];

	//Verifico que no exista el articulo en la linea de la factura, si existe sumo cantidad
	$obj = new Consultas('factulineaptmp');
	$obj->Select('codigo, cantidad' );
	$obj->Where('codfactura', $codfacturatmp, '='); 
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
		$obj = new Consultas('factulineaptmp');
		$obj->Update($nombres, $valores );
		$obj->Where('codfactura', $codfacturatmp, '='); 
		$obj->Where('codigo', $_POST['codarticulo']);   
	
		$paciente = $obj->Ejecutar();
		$paciente['consulta'];
	
	}else{

		$nombres = array();
		$valores = array();

		$nombres[] = "codfactura";
		$valores[] = $codfacturatmp;
    $nombres[] = "codproveedor";
    $valores[] = $_POST["codproveedor"];
		$nombres[] = "codfamilia";
		$valores[] = $_POST["codfamilia"];
		$nombres[] = "codigo";
		$valores[] = $_POST["codarticulo"];
		$nombres[] = "cantidad";
		$valores[] = $_POST["cantidad"];
		$nombres[] = "precio";
		$valores[] = $_POST["precio"];
		$nombres[] = "importe";
		$valores[] = $_POST["importe"];
		$nombres[] = "dcto";
		$valores[] = $_POST["descuento"];

		$objTmp = new Consultas('factulineaptmp');
		$objTmp->Insert($nombres, $valores);
		$resultado=$objTmp->Ejecutar();
	}
}

$total_rows=0;
if(strlen($codfacturatmp)>0){
$obj = new Consultas('factulineaptmp');
$obj->Select('factulineaptmp.numlinea, factulineaptmp.codfactura, factulineaptmp.codfamilia, factulineaptmp.cantidad, factulineaptmp.precio, factulineaptmp.importe, factulineaptmp.dcto, factulineaptmp.codigo,
 articulos.codarticulo, articulos.descripcion, familias.nombre as nombrefamilia ');
$obj->Join('codigo', 'articulos', 'INNER', 'factulineaptmp', 'codarticulo' );
$obj->Join('codfamilia', 'articulos', 'INNER', 'familias', 'codfamilia' );

$obj->Where('codfactura', $codfacturatmp);    
$obj->Where('borrado', '0', '', '', '', 'factulineaptmp');    

$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
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
            <th><?php echo _('C??digo');?></th>
            <th>&nbsp;<?php echo _('Descripci??n');?></th>
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
$total_importe=0.0;


if($total_rows>0){

foreach($rows as $row){
    if ($row['borrado'] ==1) { $fondolinea="danger"; } else { $fondolinea=""; }

        echo "<tr class=\"btn-inverse trigger ".$fondolinea."\" data-codfactura=\"".$row['codfactura']."\" >";

        echo "<td class=\"fit\">";
        echo $row['codarticulo'];
        echo "</td>";
        echo "<td>".$row['descripcion']. "</td>";
        echo "<td>".$row['cantidad'];
        echo "</td>";
        echo "<td>".number_format($row['precio'],2,",",".")."</td>";
        echo "<td>".$row['dcto']. "</td>";

		$importe=(float)$row["importe"];
		$total_importe=(float)$total_importe+$importe;
        echo "<td>".number_format($importe,2,",","."). "</td>";

        
        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<td>";
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar_linea('".$codfacturatmp."','". $row['numlinea']."');\"></span>";
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
parent.pon_baseimponible(Number(<?php echo json_encode($total_importe);?>));
</script>