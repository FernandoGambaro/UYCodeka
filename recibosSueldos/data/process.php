<?php
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.


use thiagoalessio\TesseractOCR\TesseractOCR;
use Spatie\PdfToText\Pdf;

use setasign\Fpdi\Fpdi;

require_once __DIR__ .'/../../fpdf/fpdf.php';   
require_once __DIR__ .'/../../library/fpdi/src/autoload.php';   

require_once '../vendor/autoload.php';
require_once __DIR__ .'/../../common/funcionesvarias.php';   
require_once __DIR__ .'/../../library/pdf-to-text/vendor/autoload.php';   


require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

//Defino algunos valores de variables
$referencia=isset($_REQUEST['referencia'])? $_REQUEST['referencia'] : '';
$tipo=$tipoaux=isset($_REQUEST['tipo'])? $_REQUEST['tipo'] : '';
$extension=isset($_REQUEST['extension']) ? $_REQUEST['extension'] : '';
$filename='procesar/'.$_REQUEST['file'];
$statusTxt='';
$status=0;
$empleado='';
$EmpleadoTexto='';
$ciTexto='';
$ci='';
$liqTexto='';
$fileMes ='';
$destinationFolder = 'procesados/';
$arrayTipo=array(1=>"Recibos Sueldo", 2=>"Salario vacacional", 3=>"Aginaldo", 4=> "Resumen IRPF");
$tipoaux=$arrayTipo[$tipoaux];

if(strlen($filename)>0 and file_exists($filename)){

	if($extension=='pdf'){
		$filen=$filename;
		$file_name = str_replace(' ' , '', basename($filename, '.pdf'));
	
		$texto = (new Pdf())
		->setPdf($filename)
		->text();
		rename("procesar/".basename($filename), 'procesar/'.$file_name.'tmp.'.$extension);

	}else{
		$extension='png';
		/*
		Para mejorar la imagen y poder extraer mejores datos
		*/
		$filen=$filename;
		$file_name = str_replace(' ' , '', basename($filename, '.png'));

		rename("procesar/".basename($filename), 'procesar/'.$file_name.'tmp.'.$extension);
		$filename='procesar/'.$file_name.'.'.$extension;
		$img = new imagick();
		$img->setResolution(300,300);
		$img->readImage($filename);

		$img->setImageBackgroundColor('#ffffff');
		$img->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
		$img->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
		$img->resizeImage(1240,1754, Imagick::FILTER_LANCZOS, 0.9, true);
		// Compress Image Quality
		$img->setImageCompressionQuality(90);
		// Set image format
		$img->setImageFormat('png');
		// Write Images to temp 'upload' folder    
		$img->resampleImage(300, 300, \Imagick::FILTER_LANCZOS, 1); 
		$img->writeImage('procesar/'.$file_name.$extension);


		$filename='procesar/'.$file_name.'.'.$extension;


		$tesseract = new TesseractOCR($filename);
		$tesseract->psm(6);
		$tesseract->textord_heavy_nr(1);
		$tesseract->threadLimit(2);
		$tesseract->lang('eng', 'spa');
		$texto = strip_tags($tesseract->run());

			$filename='procesar/'.$file_name.'tmp.'.$extension;
	}

	$texto=trim(preg_replace('/\s\s+/', ' ',$texto));

	$aux="Resolución Nº 501/011 ";
	if(strpos($texto, $aux)!==false){
		$tipo=4;
	}

	if($tipo!=4){

		//Proceso los recibos de sueldo
		//verifico que se pueda leer la C.I. en alguna de las variaciones que pueden aparecer
		$arrayCI = array('CI:', 'Cl:', 'cu:', 'c1:', 'C.I.' );
		foreach($arrayCI as $valor){
			if(strpos($texto, $valor)!==false){
				$ciTexto=$valor;
				break;
			}
		}
		//verifico que se pueda leer Liquidación en alguna de las variaciones que pueden aparecer
		$arrayLiquidacion = array('liquidación:', 'Liquidación:', 'liquidacién', "liquidación", "liquidacién", "liquidacion", "nquidacién", "llquidacion", "quuidacion", "quuidacion", "liquidaciOn", "quuidacién" );
		foreach($arrayLiquidacion as $valor){
			if(strpos($texto, $valor)!==false){
				$liquidacionTexto=$valor;
				break;
			}
		}	
		//verifico que se pueda leer Liq en alguna de las variaciones que pueden aparecer
		$arrayLiq = array('Liq.:', 'liq.:', 'Li');
		foreach($arrayLiq as $valor){
			if(strpos($texto, $valor)!==false){
				$liqTexto=$valor;
				break;
			}
		}	
		//Extraigo los dígitos de la CI hasta el guión
		if(strlen($ciTexto)>0){
			$largo=strlen($ciTexto);
			$start=strpos($texto, $ciTexto)+$largo+1;
			$ci=substr($texto, $start, 7);
			$ci=preg_replace('/\D/', '', $ci);
		}
		//Extraigo el número de empleado	
		if(strpos($texto, 'Empleado:')!==false){
			$EmpleadoTexto='Empleado:';
		}elseif(strpos($texto, 'EMPlead°=')!==false){
			$EmpleadoTexto='EMPlead°=';
		}

		if(strlen($EmpleadoTexto)>0){
			$start=strpos($texto, $EmpleadoTexto)+strlen($EmpleadoTexto)+1;
			$empleado=substr($texto, $start, 4);
		}

		//Extraigo texto de la liquidación
		if(strlen($liquidacionTexto)>0){
			$start=strpos($texto, $liquidacionTexto)+strlen($liquidacionTexto)+1;
			$liquidacion=substr($texto, $start, 6);
			$New=explode('/',$liquidacion );
			$mes = $New[0];
				//verifico que la fecha de liquidación coincida con el mes, caso contrario, tomo el mes
				$LiquidacionMes = array('enero', "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "setiembre", "octubre", "noviembre", "diciembre" );
				$x=1;
				foreach($LiquidacionMes as $valor){
					if(stripos($texto, $valor)!==false){
						//echo "<br>".$valor."<br>";
						if($mes==$x){
							$mes=$x;
							break;
						}
					}
				$x++;
				}	
			$fileMes = mes($mes);
			$anio = $New[1];
		}

		//Extraigo texto del tipo de liq.n
		/*
		if(strlen($liqTexto)>0){
			$start=strpos($texto, $liqTexto)+strlen($liqTexto)+1;
			$end=strpos($texto, 'Mes', $start);
			$liq=substr($texto, $start, ($end-$start-1));
			$liq=str_replace(" ", '-',$liq);
		}
		*/

		if(strlen($empleado)<=0 and strlen($ci)<=0){
			$statusTxt='Error al procesar archivoo, datos del empleado no válidos';
		}
		
		//Con los datos de C.I. y número de empleado los busco en la base de datos
		//Prioriso la búsqueda por número de empleado sobre la CI

	}else{
		//Proceso los resumenes de IRPF
		$arrayCI = array("CI " );
		foreach($arrayCI as $valor){
			if(strpos($texto, $valor)!==false){
				$ciTexto=$valor;
				break;
			}
		}

		if(strlen($ciTexto)>0){
			$largo=strlen($ciTexto);
			$start=strpos($texto, $ciTexto)+$largo;
			$ci=substr($texto, $start, 7);
			$ci=preg_replace('/\D/', '', $ci);
		}

		$arrayMeses = array("Ene a Dic-", "Ene a Die-",'Enea F0', 'Ene a Ago' );
		foreach($arrayMeses as $valor){
			if(strpos($texto, $valor)!==false){
				$mesesTexto=$valor;
				break;
			}
		}		
		//Extraigo los dígitos de la CI hasta el guión
		if(strlen($mesesTexto)>0){
			$largo=strlen($mesesTexto);
			$start=strpos($texto, $mesesTexto)+$largo;
			$anio="20".$fileMes=substr($texto, $start, 2);
		}		
		$liq="IRPF";

		//FinIRPF
	}

/*
		echo "<br>liquidacionTexto_ ".$liquidacionTexto."<br>";
		echo "<br>fileMes -> ".$fileMes."<br>";
		echo "<br>ci: ".$ciTexto." --- ".$ci."<br>";
		echo "año: ".$anio."<br>";
		echo $filename."<br>";
		echo "<br>Liq -> ".$liq."<br>" ;
*/


	if(strlen($empleado)>0 or strlen($ci)>0){
		
		$obj = new Consultas('usuarios');
		$obj->Select('codusuarios, nombre, apellido, mediopago, cta, nempleado');
		if(strlen($ci)>0 and strlen($empleado)<=0){
			$obj->Where('ci', $ci, 'LIKE');
		}
		if(strlen($ci)>0 and strlen($empleado)>0){
			$obj->Where('nempleado', $empleado, '=');
		}
		$usuario = $obj->Ejecutar();
		//echo "<br>".$usuario['consulta']."<br>";
		//echo $usuario['numfilas'];
		if($usuario['numfilas']==1){
			$usuario = $usuario['datos'][0];
			//echo $usuario['nombre']. ' - '. $usuario['apellido'];
			$mediopago = $usuario['mediopago'];
			$cta = $usuario['cta'];
			$empleado = $usuario['nempleado'];
			$codusuarios = $usuario['codusuarios'];

			$filename="procesar/".$empleado.'-'.$anio.'.'.$extension;

			if($extension=='png'){
				// Create an image from button.png
				$image= imagecreatefrompng($filename);
				// Set the font colour
				$colour = imagecolorallocate($image, 255, 255, 255);
				// Set the font
				$font = '../../library/fonts/ttf/arial.ttf';
				// Allocate A Color For The background
				$bcolor=imagecolorallocate($image, 46, 46, 46);
				//Create background
				imagefilledrectangle($image,  382, 57, 680, 117, $bcolor);
				// Create an image using our original image and adding the detail
				//imagettftext(IMAGE, FONT SIZE, TILT ANGLE, X, Y, COLOR, FONT, TEXT)
				imagettftext($image, 10, 0, 385, 73, $colour, $font, "Medio de pago: ". $mediopago);
				imagettftext($image, 10, 0, 385, 90, $colour, $font, "Cta# ". $cta);
				imagettftext($image, 10, 0, 385, 109, $colour, $font, "Ref# ". $referencia);
				// Output the image as a png
				imagepng($image, $filename);

			}else{

				$filename='procesar/'.$file_name.'tmp.'.$extension;
				if($tipo!=4){
				$pdf = new FPDI('Portrait','mm',array(215.9,279.4)); // Array sets the X, Y dimensions in mm
				$pdf->AddPage();
				$pagecount = $pdf->setSourceFile($filename);
				$tppl = $pdf->importPage(1);
				 
				$pdf->useTemplate($tppl, 0, 0, 215.9);
				 
				//$pdf->Image($image,10,10,50,50); // X start, Y start, X width, Y width in mm
				$style4 = array('L' => 0,
                'T' => array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => '20,10', 'phase' => 10, 'color' => array(100, 100, 255)),
                'R' => array('width' => 0.50, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(50, 50, 127)),
                'B' => array('width' => 0.75, 'cap' => 'square', 'join' => 'miter', 'dash' => '30,10,5,10'));

				$pdf->Rect(67, 8, 50, 12, 'DF', $style4, array(220, 220, 200));

				$pdf->SetFont('Helvetica','',7); // Font Name, Font Style (eg. 'B' for Bold), Font Size
				$pdf->SetTextColor(255,255,255); // RGB 
				$pdf->SetXY(67, 10); // X start, Y start in mm
				$pdf->Write(0, "Medio de pago: ". $mediopago);
				$pdf->SetXY(67, 14); // X start, Y start in mm
				$pdf->Write(0, "Cta# ". $cta);
				$pdf->SetXY(67, 18); // X start, Y start in mm
				$pdf->Write(0, "Ref# ". $referencia);
				 
				$pdf->Output($filename, "F");

				//rename('procesar/'.$file_name.'tmp.'.$extension, "procesar/".$empleado.'-'.$anio.'.'.$extension);
				}
			}

			if(file_exists($filename)){

				if(rename($filename, $destinationFolder .$empleado.'-'.str_replace(' ', '', $tipoaux).'-'.$fileMes.'-'.$anio.'.'.$extension) ){

					$objrecibosSueldos = new Consultas('recibosSueldos');
					$objrecibosSueldos->Select('archivo');
					$objrecibosSueldos->Where('archivo', $destinationFolder .$empleado.'-'.str_replace(' ', '', $tipoaux).'-'.$fileMes.'-'.$anio.'.'.$extension, '=');
					$recibosSueldos = $objrecibosSueldos->Ejecutar();
					//echo "<br>". $recibosSueldos['consulta']."<br>";
					
					if($recibosSueldos['numfilas']<=0){ 
						$nombres[] = 'codusuarios';
						$valores[] = $codusuarios;
						$nombres[] = 'ci';
						$valores[] = $ci;
						$nombres[] = 'nempleado';
						$valores[] = $empleado;
		
						$nombres[] = 'mes';
						$valores[] = $mes;
						$nombres[] = 'anio';
						$valores[] = $anio;
		
						$nombres[] = 'fecha';
						$valores[] = date("Y-m-d");
						$nombres[] = 'tipoliq';
						$valores[] = $tipoaux;
		
						$nombres[] = 'mediopago';
						$valores[] = $mediopago;
						$nombres[] = 'cta';
						$valores[] = $cta;
		
						$nombres[] = 'archivo';
						$valores[] =  $destinationFolder .$empleado.'-'.str_replace(' ', '', $tipoaux).'-'.$fileMes.'-'.$anio.'.'.$extension;
		
		
						$InsertrecibosSueldos = new Consultas('recibosSueldos');
						$InsertrecibosSueldos->Insert($nombres, $valores);
						$InsertrecibosSueldos = $InsertrecibosSueldos->Ejecutar();
			
						$statusTxt.='Datos guardados<br>';
						$status=1;
		
					}else{
						$statusTxt.='Procesado con éxito<br>';
						$status=1;
					}

					$statusTxt.='Archivo copiado<br>';
					$status=1;
				}else{
					if(file_exists($filename)){
						//unlink($filename);
					}
					$statusTxt.='Error procesando archivo<br>';	
				}
			}else{
				$statusTxt.='No se pudo copiar el archivo<br>';
			}

		}elseif($usuario['numfilas']==0){
				$statusTxt='<h4 style="color: #FF0000;">Error, no se encontró cedula Nº '.$ci.' en la base de datos</h4>';
				$filename='';
				
		}
		if(file_exists('procesar/'.$file_name.'tmp.'.$extension)){
			unlink('procesar/'.$file_name.'tmp.'.$extension);
		}
	}
		// Borra archivos temporales si es que existen
		/*
		*/

	header('Content-Type: application/json');
	echo json_encode(array('file' => basename($filen), 'fileDest' => $filename , 'statusTxt' => $statusTxt, 'status' => $status, 'ci' => $ci, 'empleado' => $empleado ));
}else{
	$statusTxt = 'Archivo no existe';
	$status = 0;
	header('Content-Type: application/json');
	echo json_encode(array('file' => basename($filen), 'fileDest' => ' ', 'statusTxt' => $statusTxt, 'status' => $status, 'ci' => $ci, 'empleado' => $empleado ));
}
?>