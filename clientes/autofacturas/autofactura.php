<?php
/**
 * UYCODEKA
 * Copyright (C) MCC (http://www.mcc.com.uy)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */
 
// set page headers
$page_title = _('Editar detalles de la factura programada'); 
//include_once "header.php";

require_once __DIR__ .'/../../common/fechas.php';   
require_once __DIR__ .'/../../common/funcionesvarias.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


date_default_timezone_set("America/Montevideo"); 
setlocale(LC_ALL,"es-ES"); 

function weekOfMonth($qDate) {
    $dt = strtotime($qDate);
    $day  = date('j',$dt);
    $month = date('m',$dt);
    $year = date('Y',$dt);
    $totalDays = date('t',$dt);
    $weekCnt = 1;
    $retWeek = 0;
    for($i=1;$i<=$totalDays;$i++) {
        $curDay = date("N", mktime(0,0,0,$month,$i,$year));
        if($curDay==6) {
            if($i==$day) {
                $retWeek = $weekCnt+1;
            }
            $weekCnt++;
        } else {
            if($i==$day) {
                $retWeek = $weekCnt;
            }
        }
    }
    return $retWeek;
}

$dia=(int)date("w"); //El Día de la semana lo comienzo en Domingo.
$semana = (int)weekOfMonth(date("Y-m-d"));
$tipof = array(1=>"Solo registrar", 2=>"Registrar y emitir", 3=>"Registrar y enviar", 4=>"Registrar, emitir y enviar");
//echo "inicio<br>";
$codautofactura=isset($_GET['codautofactura']) ? $_GET['codautofactura'] : '';

$obj = new Consultas('autofacturas');
$obj->Select();

if(strlen($codautofactura)==0) {

	$obj->Where('semanafacturacion', $semana);
	$obj->Where('diafacturacion', $dia);
	$obj->Where('activa', '1');

/*Para el día de hoy verifico si existe alguna autofactura para hacer*/

echo "<p>Para el día de hoy verifico si existe alguna autofactura para hacer<p>";
} else {

	$obj->Where('codautofactura', $codautofactura);

//$sql="SELECT * FROM autofacturas WHERE codautofactura=".$_GET['codautofactura']. " ";
echo "<p>Verifico y emito manualmente la autofactura<p>";
}

$resultado=$obj->Ejecutar();
$filas=$resultado['datos'];

//echo '<br>'.$resultado['consulta'];
if($resultado['numfilas']>0) {

	foreach($filas as $fila){
		$codautofactura= $fila['codautofactura'];
	//$count=0;
	//while($count<mysqli_num_rows($consulta)) {
	//$codautofactura=mysqli_result($consulta,  $count,  "codautofactura");
	/*Compruebo que no lo he facturado previamente*/
	//echo 	"/*Compruebo que no lo he facturado previamente*/<p>";

	$accion=$fial["accion"];

	$anio=(int)date("Y");
	$mes=(int)date("m");
	$fechahoy=date("Y-m-d");

	$historial=new Consultas('historialautofactura');
	$historial->Select();
	$historial->Where('codautofactura', $codautofactura);
	$historial->Where('diafacturacion', $fila['diafacturacion']);
	$historial->Where('semanafacturacion', $fila['semanafacturacion']);
	$historial->Where('anio', $anio);
	$historial->Where('mes', $mes);

	$ResulHistorial=$historial->Ejecutar();

		//echo '<br>'.$ResulHistorial['consulta'];
		echo '<br>';
		
		if($ResulHistorial['numfilas']==0) {

					$nombres = array();
					$valores = array();

					$nombres[] = 'codcliente';
					$valores[] = $fila["codcliente"];
					$nombres[] = 'tipo';
					$valores[] = $fila["tipo"];
					$nombres[] = 'moneda';
					$valores[] = $fila["moneda"];
					$nombres[] = 'tipocambio';
					$valores[] = $fila["tipocambio"];
					$nombres[] = 'iva';
					$valores[] = $fila["iva"];
					$nombres[] = 'estado';
					$valores[] = '1';
					$nombres[] = 'fecha';
					$valores[] = $fechahoy;
					$nombres[] = 'codformapago';
					$valores[] = $fila["codformapago"];
					$nombres[] = 'observacion';
					$valores[] = $fila["observacion"];
					$nombres[] = 'descuento';
					$valores[] = $fila["descuento"];

					$obFactura= new Consultas('facturas');
					$obFactura->Select();
					$obFactura->Orden('codfactura', 'DESC');
					$obFactura->Limit(0,1);
					$ResFactura=$obFactura->Ejecutar();
					//echo '<br>'.$ResFactura['consulta'];

					$codfactura=(int)$ResFactura['datos'][0]['codfactura']+1;

					$nombres[] = 'codfactura';
					$valores[] = $codfactura;

					$obFactura= new Consultas('facturas');
					$obFactura->Insert($nombres, $valores);
					
					$ResFactura=$obFactura->Ejecutar();

					if($ResFactura['estado']=='ok'){

						$objAutoLinea = new Consultas('autofactulinea');
						$objAutoLinea->Select();
						$objAutoLinea->Where('codautofactura', $codautofactura, '=');
						$objAutoLinea->Orden('numlinea', 'ASC');
						$ResAutoLinea=$objAutoLinea->Ejecutar();
						$rows=$ResAutoLinea['datos'];

						$baseimponible=0;
						if($ResAutoLinea['numfilas']>0){
							
							foreach($rows as $row){

								$nombres = array();
								$valores = array();

								$nombres[] = 'codfactura';
								$valores[] = $codfactura;
								$nombres[] = 'codfamilia';
								$valores[] = $row["codfamilia"];
								$nombres[] = 'codigo';
								$valores[] = $row["codigo"];
								$text = array("*mesvencido*", "*mesactual*"); 
								$mesvencido = date("m", mktime(0, 0, 0, date("m")-1,date("d"),date("Y")));
								$mesvencido=" - ".genMonth_Text($mesvencido).' '.date("Y", mktime(0, 0, 0, date("m")-1,date("d"),date("Y")));
								$mesactual = date("m", mktime(0, 0, 0, date("m"),date("d"),date("Y")));
								$mesactual=" - ".genMonth_Text($mesactual).' '.date("Y", mktime(0, 0, 0, date("m"),date("d"),date("Y")));
								$replace = array($mesvencido, $mesactual);
								$detalles = str_replace($text, $replace, $row["detalles"]); 

								$nombres[] = 'detalles';
								$valores[] = $detalles;
								$nombres[] = 'cantidad';
								$valores[] = $row["cantidad"];
								$nombres[] = 'moneda';
								$valores[] = $row["moneda"];
								$nombres[] = 'precio';
								$valores[] = $row["precio"];
								$nombres[] = 'importe';
								$valores[] = $row["importe"];
								$baseimponible=$baseimponible+$row["importe"];

								$nombres[] = 'dcto';
								$valores[] = $row["dcto"];
								if(strlen($row['dctopp']>0)){
									$nombres[] = 'dctopp';
									$valores[] = $row["dctopp"];
								}
								if(strlen($row['comision'])>0){
									$nombres[] = 'comision';
									$valores[] = $row["comision"];
								}

								if($codfactura!='') {

									$obLinea= new Consultas('factulinea');
									$obLinea->Insert($nombres, $valores);
									//var_dump($obLinea);
									$obLinea->Ejecutar();

									/*Actualizo el stock del articulo*/

									$lineaArticulo = new Consultas('articulos');
									$lineaArticulo->Select('stock');
									$lineaArticulo->Where('codarticulo', $row["codigo"]);
									$ConsultaArticulo = $lineaArticulo->Ejecutar();
									$ConsultaDatos = $ConsultaArticulo['datos'][0];
					
									$NuevoStock = (int)$ConsultaDatos['stock'] - (int)$row["cantidad"];
									
									$nombres = array();
									$valores = array();
									$nombres[] = "stock";
									$valores[] = $NuevoStock;
						
									$lineaArticulo = new Consultas('articulos');
									$lineaArticulo->Update($nombres, $valores);
									$lineaArticulo->Where('codarticulo', $row["codigo"]);
									$ConsultaArticulo = $lineaArticulo->Ejecutar();  

								}
						
							}

						}	

						$objMon = new Consultas('impuestos');
						$objMon->Select();
						$objMon->where('codimpuesto', $fila["iva"]);
						$objMon->Where('borrado', '0');
						$selMon=$objMon->Ejecutar();
						
						$filasMon=$selMon['datos'][0];
						$ivavalor=$filasMon['valor'];

						if ($fila["descuento"]==0 or $fila["descuento"]=='') {
							$baseimpuestos=$baseimponible*($ivavalor/100);
							$preciototal=$baseimponible+$baseimpuestos;
						} else {
							$baseimponibledescuento=$baseimponible/(1-$fila["descuento"]/100);
							$baseimponible=$baseimponible*(1-$fila["descuento"]/100);
							$baseimpuestos=$baseimponible*($ivavalor/100);
							$preciototal=$baseimponible+$baseimpuestos;

						}

						$nombres = array();
						$valores = array();
						$nombres[] = "totalfactura";
						$valores[] = $preciototal;

						$objfacturas = new Consultas('facturas');
						$objfacturas->Update($nombres, $valores);
						$objfacturas->Where('codfactura', $codfactura);
						$Consultafacturas = $objfacturas->Ejecutar(); 
						//echo '<br> pppppp '. $Consultafacturas['consulta'];

						/*Actualizo el historial de las autofacturas*/

						$nombres = array();
						$valores = array();
						$nombres[] = "codautofactura";
						$valores[] = $codautofactura;
						$nombres[] = "diafacturacion";
						$valores[] = $fila['diafacturacion'];
						$nombres[] = "semanafacturacion";
						$valores[] = $fila['semanafacturacion'];
						$nombres[] = "anio";
						$valores[] = $anio;
						$nombres[] = "mes";
						$valores[] = $mes;
						$nombres[] = "codfactura";
						$valores[] = $codfactura;

						$objHistorial = new Consultas('historialautofactura');
						$objHistorial->Insert($nombres, $valores);
						$Consultafacturas = $objHistorial->Ejecutar(); 

						/*Realizo la acción de Emitir y/o Enviar */
						$tipof = array(1=>"Solo registrar", 2=>"Registrar y emitir", 3=>"Registrar y enviar", 4=>"Registrar, emitir y enviar");

						if(strlen($codautofactura)==0) {

							switch($accion) {
								case 1:{
								echo "<br>Solo registrar";
								break;				
								}
								case 2:{
								echo "<br>Registrar y emitir";
								?>
								<script type="text/javascript" >
								var codfactura=<?php echo $codfactura;?>;
								var top = window.open("../../fpdf/imprimir_factura_mail.php?codfactura="+codfactura, "factura", "location=1,status=1,scrollbars=1");
								</script>
								<?php
								echo $tipof[2];
								break;
								}
								case 3:{
								echo "<br>Registrar y enviar";
								?>
								<script type="text/javascript" >
								var codfactura=<?php echo $codfactura;?>;
								windowObjectReference = window.open('../../enviomail/envia.php',"EnvioMail", "resizable,scrollbars,status, width=300,height=110,right=300,top=200");
								setTimeout(function() {windowObjectReference.location.href="../../fpdf/imprimir_factura_mail.php?codfactura="+codfactura+"&envio=1"}, 1000);
								</script>
								<?php
								echo $tipof[3];
								break;				
								}
								case 4: {
								echo "<br>Registrer, emitir y enviar";
								?>
								<script type="text/javascript" >
								var codfactura=<?php echo $codfactura;?>;
								var top = window.open("../../fpdf/imprimir_factura_mail.php?codfactura="+codfactura, "factura", "location=1,status=1,scrollbars=1");

								windowObjectReference = window.open('../../enviomail/envia.php',"EnvioMail", "resizable,scrollbars,status, width=300,height=110,right=300,top=200");
								setTimeout(function() {windowObjectReference.location.href="../../fpdf/imprimir_factura_mail.php?codautofactura="+codfactura+"&envio=1"}, 1000);
								</script>
								<?php

								echo $tipof[4];
								break;
								}
							}

						} else {
							echo "<script>parent.$('idOfDomElement').colorbox.close();</script>";
						}
				}else{
					echo "No se pudo agregar la factura";
				}
			
		}else{
			echo "Factura ya emitida para este mes";
		}
	}	//Termina el foreach de las facturas
	
}else{
 echo "No hay factura para emitir el día de hoy!!!";
}
?>

	<script>setTimeout(function(){ parent.$('idOfDomElement').colorbox.close();}, 1000);</script>