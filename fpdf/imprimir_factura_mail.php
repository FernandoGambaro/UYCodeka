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

class QRGenerator { 
 
    protected $size; 
    protected $data; 
    protected $encoding; 
    protected $errorCorrectionLevel; 
    protected $marginInRows; 
    protected $debug; 
 
    public function __construct($data='https://www.mcc.com.uy',$size='300',$encoding='UTF-8',$errorCorrectionLevel='L',$marginInRows=4,$debug=false) { 
 
        $this->data=urlencode($data); 
        $this->size=($size>100 && $size<800)? $size : 300; 
        $this->encoding=($encoding == 'Shift_JIS' || $encoding == 'ISO-8859-1' || $encoding == 'UTF-8') ? $encoding : 'UTF-8'; 
        $this->errorCorrectionLevel=($errorCorrectionLevel == 'L' || $errorCorrectionLevel == 'M' || $errorCorrectionLevel == 'Q' || $errorCorrectionLevel == 'H') ?  $errorCorrectionLevel : 'L';
        $this->marginInRows=($marginInRows>0 && $marginInRows<10) ? $marginInRows:4; 
        $this->debug = ($debug==true)? true:false;     
    }
	public function generate(){ 
        $QRLink = "https://chart.googleapis.com/chart?cht=qr&chs=".$this->size."x".$this->size.                            "&chl=" . $this->data .  
                   "&choe=" . $this->encoding . 
                   "&chld=" . $this->errorCorrectionLevel . "|" . $this->marginInRows; 
        if ($this->debug) echo   $QRLink;          
        return $QRLink; 
    }
}

define('FPDF_FONTPATH','font/');

require_once __DIR__ .'/../funciones/fechas.php'; 

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

require 'fpdf.php';
include "comunes_factura.php";
require 'pdf_js.php';


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

class PDF_Rotate extends PDF_AutoPrint
{
var $angle=0;

	function Rotate($angle,$x=-1,$y=-1){
    if($x==-1)
        $x=$this->x;
    if($y==-1)
        $y=$this->y;
    if($this->angle!=0)
        $this->_out('Q');
    $this->angle=$angle;
    if($angle!=0)
    {
        $angle*=M_PI/180;
        $c=cos($angle);
        $s=sin($angle);
        $cx=$x*$this->k;
        $cy=($this->h-$y)*$this->k;
        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    }
	}

	function _endpage() {
    if($this->angle!=0)
    {
        $this->angle=0;
        $this->_out('Q');
    }
    parent::_endpage();
	}
}

class R_PDF extends PDF_Rotate
{
	function Header(){
    //Put the watermark
    $this->SetFont('Arial','B',45);
    $this->SetTextColor(255,192,203);
    $this->RotatedText(50,190,'ENVIO POR MAIL',50);
    $this->RotatedText(35,240,'NO SUSTITUYE A LA FACTURA',50);

	}

	function RotatedText($x, $y, $txt, $angle){
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
	}
}

function forceRoundUp($value, $decimals)
{
    $ord = pow(10, $decimals);
    return ceil($value * $ord) / $ord;
}

$codfactura=isset($_GET["codfactura"]) ? $_GET["codfactura"] : $_POST["codfactura"];
$envio=isset($_GET['envio']) ? $_GET['envio'] : $_POST['envio'];  

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



if ($envio==1) {
$pdf=new R_PDF('P','mm',array(210,297));
} else {
$pdf=new PDF_AutoPrint('P', 'mm', array(210, 297));
}

//$pdf->Open();
$pdf->SetMargins(0, 0 , 0);

$pdf->AddPage('P');
$pdf->AddFont('MyriadPro-LightSemiCn','','myriadpro-lightsemicn.php');
$pdf->AddFont('Euro','','Eurocl15.php');
$pdf->SetAutoPageBreak('auto' ,3);
  
if ($envio==1) {
	$pdf->Image('../img/basico2017-A4.png', 0, 0, 210, 297);
}  

	$pdf->Image('../img/content.png',20,190,0,0,'PNG');

	$obj = new Consultas('facturas');
	$obj->Select('facturas.codcliente,facturas.fecha, facturas.tipo, facturas.codformapago, facturas.enviada, facturas.emitida, facturas.codfactura,
	 clientes.nombre, clientes.apellido, clientes.empresa, clientes.direccion, clientes.nif, clientes.email, facturas.totalfactura,facturas.estado,
	  facturas.moneda, facturas.iva, facturas.observacion ');
	$obj->Join('codcliente', 'clientes', 'INNER', 'facturas', 'codcliente' );
	$obj->Where('codfactura', $codfactura);
	$facturaEje=$obj->Ejecutar();

	$lafila=$facturaEje['datos'][0];

	$codformapago=$lafila["codformapago"];
	$codcliente=$lafila["codcliente"];

	$pdf->SetCreator($razonsocialemisor);
	$pdf->SetAuthor($nombreemisor);
	$pdf->SetTitle('Factura');
	$pdf->SetSubject('Factura');
	$pdf->SetKeywords('');


	$ColorBlanco="255,255,255";
	$Color="77,139,180";
	$TextColor="0,0,0";

	/*Cabezal de la factura*/
	/*1er línea*/
		$pdf->Ln(13);

	/*2da línea*/
		$tipo = array( 0=>"Contado", 1=>"Crédito", 2=>"Nota de Crédito");
		$num=$lafila['tipo'];
		$tipof=$tipo[$num];
	$fecha = implota($lafila["fecha"]);
		
		$pdf->Ln(13);
		$pdf->Cell(81); /*  ubicación inicial de la celdas */
	$pdf->SetFont('Arial','',11);
		$pdf->SetTextColor($TextColor);
		$pdf->Cell(25,8,$fecha,0,0,'C',0);	   
		$pdf->Cell(1,8,'',0,0,'C',0);	
		$pdf->Cell(63,8,$tipof,0,0,'C',0);	
		$pdf->Cell(1,8,'',0,0,'C',0);	
		$pdf->Cell(29,8,$codfactura,0,0,'C',0);	
	/*3er línea*/

		if($codformapago=="" or $codformapago==0) {
		$forma="Contado";
		$dias=0;
		} else {

			$objMon = new Consultas('formapago');
			$objMon->Select();
			$objMon->Where('codformapago', $codformapago);
			$selMon=$objMon->Ejecutar();
			$filasMon=$selMon['datos'][0];
			$forma=$filasMon["nombrefp"];
			$dias=$filasMon["dias"];
		}	
		$vence = strtotime ( '+'.$dias.' day' , strtotime ( $lafila["fecha"] ) ) ;
		$vence = implota(date ( 'Y-m-j' , $vence ));
		
	/*4ta línea*/
	if (empty($lafila["nif"])){
		$rutcomprador="";
		$final="X";
		} else {
		$rutcomprador=$lafila["nif"];
		$final="";
	}
		$pdf->Ln(12);
		$pdf->Cell(80);	
		$pdf->Cell(90,8,$rutcomprador ,0,0,'C',0);	
		$pdf->Cell(1);	
		$pdf->Cell(26,8,$final ,0,0,'C',0);	
		

		$objMon = new Consultas('monedas');
		$objMon->Select();
		$objMon->Where('orden', '3', '<');
		$objMon->Where('borrado', '0');
		$objMon->Orden('orden', 'ASC');
		$selMon=$objMon->Ejecutar();
		$filasMon=$selMon['datos'];
	
		$xmon=1;
		foreach($filasMon as $fila){
			if($lafila["moneda"] == $xmon){
				$moneda=$fila['simbolo'];
			}
			$xmon++;
		}

		$pdf->Ln(13);
		$pdf->Cell(80); /*  ubicación inicial de la celdas */
		$pdf->SetFont('Arial','',11);
		$pdf->SetTextColor($TextColor);
		$pdf->Cell(60,8,$vence ,0,0,'C',0);	
		$pdf->SetTextColor($Color);
		$pdf->SetFillColor($Color);
		$pdf->Cell(1,8,'',0,0,'C',0);	
		$pdf->SetTextColor($TextColor);
		$pdf->Cell(59,8, $moneda ,0,0,'C',0);	

	/*Finalizo cabezal */
	/***************************************************************/
	/*Líneas de la factura*/
		$pdf->Ln(20);			
		
		
		if($lafila["empresa"]!='') {
		$nombre=$lafila["empresa"];
	} else {
		$nombre=$lafila["nombre"]." ".$lafila["apellido"];
	}	
		$pdf->Cell(30);
		$pdf->Cell(107,8,$nombre ,0,0,'L',0);	
		$pdf->Ln(9);			
		$pdf->Cell(30);
		$pdf->Cell(107,8,$lafila["direccion"],0,0,'L',0);    


		$pdf->SetFont('Arial','',9);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFillColor(255,255,255);
		
		$pdf->Ln(19);
		
	$objLinea = new Consultas('factulinea');
	$objLinea->Select('factulinea.numlinea, factulinea.codfactura,factulinea.detalles, factulinea.codfamilia, factulinea.cantidad, 
	factulinea.precio, factulinea.importe, factulinea.dcto, factulinea.codigo,
	articulos.codarticulo, articulos.referencia, familias.nombre as nombrefamilia ');
	$objLinea->Join('codigo', 'articulos', 'INNER', 'factulinea', 'codarticulo' );
	$objLinea->Join('codfamilia', 'articulos', 'INNER', 'familias', 'codfamilia' );

	$objLinea->Where('codfactura', $codfactura);    

	$objLinea->Orden('numlinea', 'DESC');    

	$paciente = $objLinea->Ejecutar();
	//echo "<br>".$paciente["consulta"]."<br>-->";
	$total_rows=$paciente["numfilas"];
	$rows = $paciente["datos"];

    
	$lineas=0;
	$contador=0;
	if($total_rows>0){
		foreach($rows as $row) { 
		$pos=0;
		
		$codarticulo=$row["codigo"];
		$codfamilia=$row["codfamilia"];
		$detallesA=$detalles=$row["detalles"];

			if( $codarticulo!='') {  
				$pdf->Cell(10); /*  ubicación inicial de la celdas */

				$largoDescripcion=70;
				$largo_ini=strlen($detalles);
					if($largo_ini>$largoDescripcion) {
						$largo=1;
						$texto_corto = substr($detalles, 0, $largoDescripcion);
						$pos = strripos($texto_corto,' ');
						if ($pos !== false) { 
							$acotado = substr($detallesA, 0, $pos);
							$resta=substr($detallesA, $pos, $largo_ini-$pos );
							$pos=$pos+1;
						} else {	
							$acotado = substr($detallesA, 0, $largoDescripcion);
							$resta=substr($detallesA, $pos, $largo_ini-$pos );
						}
					} else {
						$largo=0;
						$acotado = substr($detallesA, 0, $largoDescripcion);
					}
			$precio32=0;
			$importe32=0;
				if($row["importe"]!=0) {
				$importe32= number_format($row["importe"],2,",",".");	  
				$precio32= number_format($row["importe"]/$row["cantidad"],2,",",".");	 
				$importe32= number_format($row["importe"],2,",",".");	  
				} 

				$pdf->Cell(22,4, substr($row["referencia"],0,10),0,0,'L',0);	
				$pdf->Cell(1,4,'',0,0,'C',0);	
				$pdf->Cell(100,4,$acotado,0,0,'L',0);	
				$pdf->Cell(1,4,'',0,0,'C',0);	
				$pdf->Cell(18,4,$precio32,0,0,'R',0);	
				$pdf->Cell(1,4,'',0,0,'C',0);	
				$pdf->Cell(21,4,$row["cantidad"],0,0,'C',0);	
				$pdf->Cell(1,4,'',0,0,'C',0);	
				$pdf->Cell(24,4,$importe32,0,0,'R',0);	
				
				$pdf->Ln(4);
					
			$ini=$pos;
			$renglon=1;
			while ($largo==1 and $renglon<2){
				$largo_ini=strlen($resta);
				if($largo_ini>$largoDescripcion) {
					$largo=1;
					$texto_corto = substr($resta, 0, $largoDescripcion);
					$pos = strripos($texto_corto,' ');
					if ($pos !== false) { 
						$acotado = substr($detallesA, $ini, $pos);
						$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
						$pos=$pos+1;
					} else {	
						$acotado = substr($detallesA, $ini, $largoDescripcion);
						$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
					}
				} else {
					$largo=0;
					$acotado = substr($detallesA, $ini, $largoDescripcion);
				}
				$ini=$ini+$pos;	

				$pdf->Cell(10); /*  ubicación inicial de la celdas */		
				$pdf->Cell(22,4,'',0,0,'L',0);	
				$pdf->Cell(1,4,'',0,0,'C',0);	
				$pdf->Cell(100,4,$acotado,0,0,'L',0);	
				$pdf->Cell(1,4,'',0,0,'C',0);	
				$pdf->Cell(18,4,'',0,0,'R',0);	
				$pdf->Cell(1,4,'',0,0,'C',0);	
				$pdf->Cell(21,4,'',0,0,'C',0);	
				$pdf->Cell(1,4,'',0,0,'C',0);	
				$pdf->Cell(24,4,'',0,0,'R',0);	

				$pdf->Ln(5);
			$contador++;
			$renglon++;
			}
		//vamos acumulando el importe
		$importe=$importe + $row["importe"];
		$contador++;
		}

		$lineas=$lineas + 1;	  
		};
	}
	
	$importe4=number_format($importe,2,",",".");	

	$descuento=$lafila["descuento"];
	$impodescuento=$impodesc=$importe*(1-$descuento/100);
	$impodesc=sprintf("%01.2f", $impodesc); 
	$impodesc=number_format($impodesc,2,",",".");

	$objMon = new Consultas('impuestos');
	$objMon->Select();
	$objMon->Where('codimpuesto', $lafila["iva"]);
	$selMon=$objMon->Ejecutar();
	$filasMon=$selMon['datos'][0];
		$ivai=$filasMon["valor"];
		$ivatxt=$filasMon["valor"]."%";

	//$ivai=$lafila["iva"];
	$impoiva=$importeiva=$impodescuento*($ivai/100);
	
	$impoiva=sprintf("%01.2f", $impoiva); 
	$impoiva=number_format($impoiva,2,",",".");
	
	$total=round($impodescuento+$importeiva,2); 
	$total=sprintf("%01.2f", $total);
	$total=round(forceRoundUp($total, 3));
	$total2= number_format($total,2,",",".");		
	
	/*Indico el lugar donde comienza poner importe*/
	$pdf->SetY(246); 
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(255,255,255);

	$pdf->Cell(171); /*  ubicación inicial de la celdas */		
	if($lugarmon==1) {	
		$pdf->Cell(9,4,$moneda." ",0,0,'R',0);	
		$pdf->Cell(18,4,$importe4,0,0,'R',0);	
	}else {
		$pdf->Cell(18,4,$importe4,0,0,'R',0);	
		$pdf->Cell(9,4,$moneda." ",0,0,'R',0);	
	}
	$pdf->Ln(6);
	$pdf->Cell(165); /*  ubicación inicial de la celdas */			
	$pdf->Cell(6,4,$ivatxt,0,0,'R',0);	
	
	if($lugarmon==1) {	
		$pdf->Cell(9,4,$moneda." ",0,0,'R',0);	
		$pdf->Cell(18,4,$impoiva,0,0,'R',0);			
	} else {
		$pdf->Cell(18,4,$impoiva,0,0,'R',0);		
		$pdf->Cell(9,4," ".$moneda,0,0,'L',0);	
	}	

	$pdf->Ln(6);
	$pdf->Cell(171); /*  ubicación inicial de la celdas */		

	if($lugarmon==1) {	
		$pdf->Cell(9,4,$moneda." ",0,0,'R',0);	
		$pdf->Cell(18,4,$total2,0,0,'R',0);		
	} else {
		$pdf->Cell(18,4,$total2,0,0,'R',0);		
		$pdf->Cell(9,4," ".$moneda,0,0,'L',0);	
	}

/*Observaciones ****************************************************************************************/
$filasadenda=3;

	//$pdf->SetX(170);
	$pdf->SetY(263); 
	$pdf->Ln(2);	
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(255,255,255);	
	$pdf->Cell(87); /*  ubicación inicial de la celdas */		
	$filasadenda--;
	
	
	$pdf->Ln(2);	
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(107); /*  ubicación inicial de la celdas */		
	$pdf->Cell(78,4,'Forma de pago: '.$forma,0,0,'L',0);	
	$filasadenda--;
	$pdf->Ln(4);
		
	$wmax=87; //máximo de caracteres que tiene cada línea de la adenda
	$frace='';
	$s=str_replace("\r",'',utf8_decode( $lafila["observacion"])); 
	if($s!='') {
	$cantlineas=0;
	$marker='\n';
	$frace=wordwrap($s, $wmax, $marker, true );
	$lines = explode($marker, $frace);
	//var_dump($obs);
		if(is_array($lines) ) {
			foreach ($lines as $line_index=>$line) {
			$pdf->Cell(85); 
			$pdf->Cell(87,4,$line,0,0,'L',0);	
			$pdf->Ln(4);	
			$filasadenda--;
			}
		}
	} 

	
if ($envio==1) {
	/*Hago efectivo el envío por mail*/
	$filename='Factura'.$codfactura.".pdf";
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

	$documento='de la factura nº '.$codfactura;

	$text = array("*empresa*", "*nombre*", "*apellido*", "*usuario*", "*documento*", "<strong>", "</strong>", "<title>Untitled document</title>","<p>Untitled document</p>"); 
	$replace = array($empresa, $nombre, $apellido, $usuario, $documento, "<b>", "</b>","","");
	$emailbody = str_replace($text, $replace, $emailbody); 
   
	if ($uemail!='') {
		//Descomentar la siguiente línea para pruebas
		//
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

				$objEnvio= new Consultas('facturas');
				$objEnvio->Update($nombres, $valores);
				$objEnvio->Where('codfactura', $codfactura);
				$objEnvio->Ejecutar();
				$componente['status']='Documento enviado exitosamente';
			}else{
				$componente['status']= 'Fallo el envío!'. $mail->ErrorInfo;
			}		 
		} else {
			$componente['status']= 'Destinatario no válido';
		}

} else {
	/*Lo imprimo directamente*/

//Descomentar la siguiente línea para pruebas
//El documento PDF se genera en /var/spool/cups-pdf/apache
//$printer='Cups-PDF';
//$reporte=1;
	if($reporte==1) {	
	$filename='Factura'.$codfactura.".pdf";
	$file='../tmp/'.$filename;
	$pdf->Output($file, 'F');


	$componente['printer']=$printer;
	$componente['file']=$filename;

		if (file_exists($file) and $printer!='') {
			/*Si existe el archivo lo envío directamente a la impresora*/

		$comando = escapeshellcmd('lp  -d '.$printer.' -n 2 '.$file);
		$output = shell_exec($comando); /*Envío la impresión por sistema*/
		preg_match('/\d+/', $output, $match);
		$printid =$match[0];	
		$componente['print_id']= $printid;

		} else {	
		$pdf->AutoPrintToPrinter($server, $printer, false);
		$pdf->Output($file,'F');
		$pdf->Close();
		}

		$nombres=array();
		$valores=array();

		$nombres[]='emitida';
		$valores[]= 1;

		$objEnvio= new Consultas('facturas');
		$objEnvio->Update($nombres, $valores);
		$objEnvio->Where('codfactura', $codfactura);
		$objEnvio->Ejecutar();

	} else {
		/*Muestra la impresión en pantalla*/
			$pdf->Output();
	}
}

$data[]=$componente;
echo json_encode($data);
flush(); 

?>