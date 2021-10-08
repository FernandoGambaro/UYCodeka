<?php
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   

// include header file
$page_title = "Listado elementos de la factura";
include_once "header-rejilla.php";


require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

$codrecibo=isset($_GET['codrecibo']) ? $_GET['codrecibo'] : $_POST['codrecibo'];
$total_importe=0;

//var_dump($_REQUEST);

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

                $nombres[] = $key;
            }
        }

		$objTmp = new Consultas('recibospagotmp');
        $objTmp->Insert($nombres, $valores);
		$resultado=$objTmp->Ejecutar();
}

$total_rows=0;

$obj = new Consultas('recibospagotmp');
$obj->Select('recibospagotmp.codrecibo, recibospagotmp.tipo, recibospagotmp.numeroserie, recibospagotmp.numero, recibospagotmp.moneda, recibospagotmp.tipocambio,
recibospagotmp.importe, recibospagotmp.fecha, recibospagotmp.observaciones');

$obj->Where('codrecibo', $codrecibo);    

$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

/*Verifico los permisos del usuario logeado*/
$eliminar=verificopermisos('cobors', 'eliminar', $UserID);

$objMon = new Consultas('monedas');
$objMon->Select();
$objMon->Where('orden', '3', '<');
$objMon->Where('borrado', '0');
$objMon->Orden('orden', 'ASC');
$selMon=$objMon->Ejecutar();
$filasMon=$selMon['datos'];

$xmon=1;
$mon=array();

foreach($filasMon as $fila){
    $mon[$xmon] = $fila['simbolo'];
    $xmon++;
}


// check if more than 0 record found

?>
<div class="table-responsive">
    <table class='table table-hover table-responsive table-bordered table-condensed ' id="rejilla">
    <thead class="bg-primary">
        <tr>
            <th><?php echo _('Tipo');?></th>
            <th>&nbsp;<?php echo _('Banco');?></th>
            <th><?php echo _('Serie');?></th>
            <th><?php echo _('Nº Doc.');?></th>
            <th><?php echo _('Mon.');?></th>
            <th><?php echo _('Importe');?></th>
            <th><?php echo _('T/C');?></th>
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
    $total_importe=0;
    $tipofa = array( 0=>"Seleccione uno", 1=>"Contado", 2=>"Cheque",3=>"Giro Bancario", 4=>"Giro RED cobranza", 5=>"Resguardo");
foreach($rows as $row){
    if ($row['borrado'] ==1) { $fondolinea="danger"; } else { $fondolinea=""; }

        echo "<tr class=\"btn-inverse trigger ".$fondolinea."\" data-codrecibopago=\"".$row['codrecibopago']."\" >";

        echo "<td class=\"fit\">".$tipofa[$row['tipo']]."</td>";
        echo "<td>".$row['codentidad']. "</td>";
        echo "<td>".$row['numeroserie']."</td>";
        echo "<td>".$row['numero']."</td>";
        echo "<td>".$mon[$row['moneda']]. "</td>";
        $importe=(float)$row["importe"];
		$total_importe=(float)$total_importe+$importe;
        echo "<td>".number_format($importe,2,",","."). "</td>";

        echo "<td>". $row['tipocambio']. "</td>";
        // delete user button
		if ( $UserTpo == 100 or $eliminar=="true") {
            echo "<td>";
            echo "<a href='#' class='btn btn-danger delete-object btn-xs' data-dismiss='modal'>";
            echo "<span class='glyphicon glyphicon-remove' onClick=\"eliminar_linea('".$row['codrecibopago']."','". $codrecibo."', 'recibospagotmp');\"></span>";
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
console.log(Number(<?php echo $total_importe;?>));
parent.pon_cobro(Number(<?php echo $total_importe;?>));
</script>