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
 
define('FPDF_FONTPATH','font/');

require_once __DIR__ .'/../funciones/fechas.php'; 

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

require('fpdf.php');
include("comunes_factura.php");
require('pdf_js.php');


class PDF_AutoPrint extends PDF_JavaScript
{
function AutoPrint($dialog=false)
{
    //Open the print dialog or start printing immediately on the standard printer
    $param=($dialog ? 'true' : 'false');
    $script="print($param);";
    $this->IncludeJS($script);
}


function AutoPrintToPrinter($server, $printer, $dialog=false)
{
    //Print on a shared printer (requires at least Acrobat 6)
   $script = "var pp = getPrintParams();";
   if($dialog)
   	$script .= "pp.interactive = pp.constants.interactionLevel.full;";
   else
		$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
 	$script .= "pp.colorOverride = pp.constants.colorOverrides.gray;";
	$script .="var fv = pp.constants.flagValues;";
	$script .= "pp.flags |= fv.setPageSize;";
	$script .= "pp.pageHandling=1;";
	if($server=='') {
		$script .= 'pp.printerName="'.$printer.'";';
	} else {
		$script .= "pp.printerName='\\\\\\\\".$server."\\\\".$printer."';";
	} 
	$script .="print(pp);";
	$script .="closeDoc(pp);";
	$script .=" doc.Close();";
   $this->IncludeJS($script);
}
}

$codrecibo=isset($_GET["codrecibo"]) ? $_GET["codrecibo"] : $_POST["codrecibo"];
$envio=isset($_GET['envio']) ? $_GET['envio'] : $_POST['envio'];  

$consumo='';
$lineas='';
$impo='';
$importe='';
$largoDescripcion=100;


$consumo='';
$lineas='';
$impo='';
$importe=0;

$obj_datos = new Consultas('datos');
$obj_datos->Select();
$obj_datos->Where('coddatos', '0');
$datos=$obj_datos->Ejecutar();
$dato=$datos['datos'][0];
$nombreemisor=$dato['nombre'];
$razonsocialemisor=$dato['razonsocial'];
$direccionemisor=$dato['direccion'];
//$nif=$dato['nif'];
$lugarmon=$dato['lugarmon'];
$logofactura=$dato['logofactura'];

$server=$dato['servidorfactura'];
$printer=$dato['impresorafactura'];
$reporte=$dato['reporte'];

$data = array();
$componente=array();

	$moneda = array();
	
	$objMon = new Consultas('monedas');
	$objMon->Select();
	$objMon->Where('orden', '3', '<');
	$objMon->Where('borrado', '0');
	$objMon->Orden('orden', 'ASC');
	$selMon=$objMon->Ejecutar();
	$filasMon=$selMon['datos'];

	$xmon=1;
	foreach($filasMon as $fila){
		if($fila["orden"] == $xmon){
			$moneda=$fila['simbolo'];
		}
		$xmon++;
	}
						 

							 
						 
//$pdf=new FPDF('L','mm','A5');
$pdf=new PDF_AutoPrint('L','mm',array(210,145));
//$pdf=new PDF_AutoPrint('L','mm',array(210,145));

//$pdf->Open();
//$pdf->SetMargins(4, 6 , 4);
//$pdf->AddPage('L');
$pdf->AddPage();

if ($envio==1) {
	$pdf->Image('../img/MCCRecibo2017.png', 0, 0, 210, 145);
}
$pdf->AddFont('MyriadPro-LightSemiCn','','myriadpro-lightsemicn.php');

$pdf->SetAutoPageBreak('auto' ,3);


$consulta = "Select * from recibos,clientes where recibos.codrecibo='$codrecibo' AND clientes.codcliente=recibos.codcliente";
$resultado = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
$lafila=mysqli_fetch_array($resultado);

$codcliente=$lafila["codcliente"];

    $pdf->Ln(27);					

/* Establezco el color de las celdas y tipo de letra */
   $pdf->Cell(82); /* ubicación x inicial de la celdas */

   $pdf->SetFont('MyriadPro-LightSemiCn','',12);
   $fecha = implota($lafila["fecha"]);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(38,4,$fecha,0,0,'C',0);	

	$pdf->Cell(39);
	$pdf->Cell(38,4,$codrecibo,0,0,'C',0);		
	
	$pdf->Ln(11);

/* --------- */
    $pdf->Cell(22); /* ubicación inicial x de la celdas*/
	
if($lafila["empresa"]!='') {
	$nombre=$lafila["empresa"];
} else {
	$nombre=$lafila["nombre"]." ".$lafila["apellido"];
}

	$pdf->Cell(130,4,$nombre,0,0,'',0);

	$pdf->Ln(8);

    $pdf->Cell(22); /* ubicación x inicial de la celdas*/

   $importe= $moneda[$lafila["moneda"]]. " ".numtoletras($lafila["importe"]);
   $importe=strtolower($importe);
   $pdf->Cell(140,4,$importe,0,0,'L',0);

	//ahora mostramos las líneas de la factura
	//$pdf->Ln(14);		
$pdf->SetY(68);	
	 $consulta32 = "Select * from recibosfactura where codrecibo='$codrecibo' order by codfactura ASC";
    $resultado32 = mysqli_query($GLOBALS["___mysqli_ston"], $consulta32);
    
	$contador=0;
	$total=0;
	while ($contador < mysqli_num_rows($resultado32)) { 
		$pdf->Cell(12);
		$codfactura=mysqli_result($resultado32, $contador, "codfactura");
	  	$totalfactura=mysqli_result($resultado32, $contador, "totalfactura");
		$pdf->Cell(20,4,$codfactura,0,0,'C');

	$totalfactura2=round($totalfactura,2); 
   $totalfactura2=sprintf("%01.2f", $totalfactura2);
   $totalfactura2=round($totalfactura2, 0, PHP_ROUND_HALF_UP);
	$totalfactura2= number_format($totalfactura2,2,",",".");		
		
		$pdf->Cell(24,4,$totalfactura2,0,0,'R');
		
		$pdf->Ln(4);
	  $total+=$totalfactura;
	  $contador++;
	  
	};
	
	while ($contador<13)
	{
		$pdf->Cell(12);
		$pdf->Cell(20,4,"",0,0,'C');
		$pdf->Cell(24,4,"",0,0,'C');
		$pdf->Ln(4);
	  $contador=$contador +1;
	}	

		//$pdf->Ln(2);
	
		$pdf->Cell(12);
		$pdf->Cell(20,4,"",0,0,'C');

	$total3=round($total,2); 
   $total3=sprintf("%01.2f", $total3);
   $total3=round($total3, 0, PHP_ROUND_HALF_UP);
	$total3= number_format($total3,2,",",".");		
			
		
		$pdf->Cell(24,4,$total3,0,0,'R');	
/*Mostramos las líneas del pago*/	
$pdf->SetY(68);	
	
	$consulta = "Select * from recibospago where codrecibo='$codrecibo' ";
   $resultado = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
    
	$contador=0;
	$total=0;
	while ($contador < mysqli_num_rows($resultado)) { 
	
		$pdf->Cell(66);
		
	  	$query_entidades="SELECT * FROM entidades WHERE codentidad=".mysqli_result($resultado, $contador, "codentidad");
		$res_entidades=mysqli_query($GLOBALS["___mysqli_ston"], $query_entidades);

		if(mysqli_result($resultado, $contador, "tipo")==2) {	
		$tipo="Cheque";					
		$nombreentidad=mysqli_result($res_entidades, 0, "nombreentidad");
	  	$numero=mysqli_result($resultado, $contador, "numeroserie"). " - " . mysqli_result($resultado, $contador, "numero");
	  	$fechapago=implota(mysqli_result($resultado, $contador, "fechapago"));
		} elseif(mysqli_result($resultado, $contador, "tipo")==1) {
		$tipo="Contado";
		$nombreentidad="-";
		$numero="-";
		$fechapago="-";
		} elseif(mysqli_result($resultado, $contador, "tipo")==3) {
		$tipo="Giro Bancario";
		$nombreentidad=mysqli_result($res_entidades, 0, "nombreentidad");		$numero="-";
		$fechapago=implota(mysqli_result($resultado, $contador, "fechapago"));				
		} elseif(mysqli_result($resultado, $contador, "tipo")==4) {
		$tipo="Giro RED cobranza";
		$nombreentidad="-";
		$numero="-";
		$fechapago="-";
		} elseif(mysqli_result($resultado, $contador, "tipo")==5) {
		$tipo="Resguardo";
		$nombreentidad="-";
		$numero="-";
		$fechapago="-";
		} else {
		$tipo="-";
		$nombreentidad="-";
		$numero="-";
		$fechapago="-";
		}
	  	$importe=mysqli_result($resultado, $contador, "importe");

		$pdf->Cell(23,4,$tipo,0,0,'C');

	  	if (mysqli_result($resultado, $contador, "tipo")!=1) {
		$pdf->Cell(24,4,$nombreentidad,0,0,'C');
		$pdf->Cell(5);
		$pdf->Cell(28,4,$numero,0,0,'C');
		$pdf->Cell(1);
		$pdf->Cell(20,4,$fechapago,0,0,'R');
		} else {
			if(mysqli_result($resultado, $contador, "observaciones")!='') {
				$pdf->Cell(77,4,"Observaciones: ".mysqli_result($resultado, $contador, "observaciones"),0,0,'C');
			} else {
				$pdf->Cell(77);
			}
		}

	$importe2=round($importe,2); 
   $importe2=sprintf("%01.2f", $importe2);
   $importe2=round($importe2, 0, PHP_ROUND_HALF_UP);
	$importe2= number_format($importe2,2,",",".");
	
		$pdf->Cell(1);
		$pdf->Cell(20,4,$importe2,0,0,'R');
		
		$pdf->Ln(4);
	  $total+=$importe;
	  $contador++;
	  
	};

	while ($contador<13)
	{
		$pdf->Ln(4);
	  $contador=$contador +1;
	}	
		//$pdf->Ln(2);
	
	$total=round($total,2); 
   $total=sprintf("%01.2f", $total);
   $total=round($total, 0, PHP_ROUND_HALF_UP);
	$total2= number_format($total,2,",",".");		
	
		$pdf->Cell(164);
		$pdf->Cell(24,4,$total2,0,0,'R');
		
	$pdf->Ln(4);		

if ($envio==1) {
	$filename='recibo'.$codrecibo.".pdf";
	$file='../tmp/'.$filename;
	$pdf->Output($file, 'F');


	date_default_timezone_set("America/Montevideo"); 
	require_once __DIR__ .'/../classes/PHPMailerAutoload.php';
	require_once __DIR__ .'/../classes/Encryption.php';
	
	$emailname=$dato['emailname'];
	$emailsend=$dato['emailsend'];
	$emailreply=$dato['emailreplay'];
	
	$emailhostenvio=$dato['emailhostenvio'];
	$emailsslenvio=$dato['emailsslenvio'];
	$emailpuertoenvio=$dato['emailpuertoenvio'];
	$emailbody=$dato['emailbody'];
	
	$converter = new Encryption;
	$emailpass = $converter->decode($dato['emailpass']);
	
	$subject="Envio archivo adjunto ".$filename." - No responder este mail";
	$usuario=$UserNom." ".$UserApe;

	$nombre=$lafila["nombre"];
	$apellido=$lafila["apellido"];
	$empresa=$lafila["empresa"];
	$uemail=$lafila["email"];

	$documento='del comprobante de pago nº '.$codrecibo;

	$text = array("*empresa*", "*nombre*", "*apellido*", "*usuario*", "*documento*", "<strong>", "</strong>", "<title>Untitled document</title>","<p>Untitled document</p>"); 
	$replace = array($empresa, $nombre, $apellido, $usuario, $documento, "<b>", "</b>","","");
	$emailbody = str_replace($text, $replace, $emailbody); 
   
	if ($uemail!='') {
		//Descomentar la siguiente línea para pruebas
		
		$uname=$nombre." ".$apellido;
	
			$ssl=$emailsslenvio."://".$emailhostenvio.":".$emailpuertoenvio."";
			$mail = new PHPMailer(); 
			//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
			//$mail->SMTPDebug = 2;
			$mail->IsSMTP(); 
			$mail->Host = $emailhostenvio;	
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = $emailsslenvio;	
			$mail->Port =$emailpuertoenvio;		
								
			$mail->Username = $emailsend;
			$mail->Password = $emailpass;
			$mail->From = $emailsend;
			$mail->FromName = $emailname;
	
			$mail->From = $emailsend;
			$mail->FromName = $emailname;
			$mail->Subject = $subject;
			// Cuenta de E-Mail Destinatario  
			$mail->CharSet = "UTF-8";
			$mail->ClearReplyTos();
			if($emailreply!='') {
			$mail->addReplyTo($emailreply, $emailname);
			} else {
			$mail->addReplyTo($UserMail,$usuario);
			}
			/*Datos del destinatario*/
			$mail->AddAddress($uemail,$uname);
	
			/*Adjunto archivo*/
			$mail->AddAttachment("../tmp/".$file, $file);
			$mail->WordWrap = 900;
			$mail->IsHTML(true);
	
			$message = $emailbody;
				
			$mail->MsgHTML($message);
			
			if($mail->Send()){
				$nombres=array();
				$valores=array();

				$nombres[]='enviada';
				$valores[]= 1;

				$objEnvio= new Consultas('recibos');
				$objEnvio->Update($nombres, $valores);
				$objEnvio->Where('codrecibo', $codrecibo);
				$objEnvio->Ejecutar();
				$componente['status']='Documento enviado exitosamente';
			}else{
				$componente['status']= 'Fallo el envío!'. $mail->ErrorInfo;
			}		 
		} else {
			$componente['status']= 'Destinatario no válido';
		}


//	header("Location: ../enviomail/enviomail.php?codcliente=".$codcliente."&tipo=R&cod=".$codrecibo."&file=".$filename."&documento=del recibo nº ".$codrecibo);
} else {
	/*Lo imprimo directamente*/
$sql_datos="SELECT servidorfactura,impresorafactura,web,reporte  FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$server=mysqli_result($rs_datos, 0, "servidorfactura");
$printer=mysqli_result($rs_datos, 0, "impresorafactura");
$reporte=mysqli_result($rs_datos, 0, "reporte");
	

	if($reporte==1) {	
	$filename='recibo'.$codrecibo.".pdf";
	$file='../tmp/'.$filename;
	$pdf->Output($file, 'F');
	
		if (file_exists($file)) {
			//echo 'lp  -d '.$printer.' -n 2 -omedia=210x145mm -o fit-to-page '.$file;
		$output = shell_exec('lp  -d '.$printer.' -n 2  -o landscape -o fit-to-page -o media=A5 '.$file); /*Envío la impresión por sistema*/
		preg_match('/\d+/', $output, $match);
		$printid =$match[0];
		
		} else {	
		$pdf->AutoPrintToPrinter($server, $printer, false);
		$pdf->Output($file,'F');
		$pdf->Close();
		}
	} else {
		/*Muestra la impresión en pantalla*/
			$pdf->Output();
	}
}
	@((mysqli_free_result($resultado) || (is_object($resultado) && (get_class($resultado) == "mysqli_result"))) ? true : false); 
   @((mysqli_free_result($query) || (is_object($query) && (get_class($query) == "mysqli_result"))) ? true : false);
	@((mysqli_free_result($resultado2) || (is_object($resultado2) && (get_class($resultado2) == "mysqli_result"))) ? true : false); 
	@((mysqli_free_result($query3) || (is_object($query3) && (get_class($query3) == "mysqli_result"))) ? true : false);	
	$query="UPDATE recibos SET `emitido`=1 WHERE codrecibo='$codrecibo'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>