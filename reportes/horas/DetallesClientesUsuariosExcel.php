<?php
setlocale(LC_ALL,"es_ES");
session_start();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set("America/Montevideo"); 


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
require_once __DIR__ .'/../../classes/class_session.php';

if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
  }

$oidcontacto = $s->data['UserID'] ;

require_once '../../common/fechas.php';   
require_once '../../common/funcionesvarias.php';   

$codcliente=isset($_POST["codcliente"]) ? $_POST["codcliente"] : '';
$codusuario=isset($_POST["codusuario"]) ? $_POST["codusuario"] : '';


require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

    $objdatos = new Consultas('datos');
    $objdatos->Select();
    $objdatos->Where('coddatos', '0');    
    $datos = $objdatos->Ejecutar();
    $total_rows=$datos["numfilas"];
    $rows = $datos["datos"][0];
    $ShowName=$razonsocial=$rows['razonsocial'];
    $direccion=$rows['direccion'];
    $telefono=$rows['telefono1'];
    $fax=$rows['fax'];
    $email=$rows['mailv'];
    $web=$rows['web'];

    $titulo=$_GET['titulo'];
    if (strlen($_GET["fechaini"]) > 3 ) { $fechaini=$_GET["fechaini"];} else { $fechaini=date("Y-m-d") ;}
    if (strlen($_GET["fechafin"]) > 3 ) { $fechafin=$_GET["fechafin"];} else { $fechafin=date("Y-m-d") ;}
    
    //$codcliente=filter_var($codcliente, FILTER_VALIDATE_INT); 
    //$codusuario=filter_var($codusuario, FILTER_VALIDATE_INT); 

    $codcliente=filter_var($codcliente, FILTER_SANITIZE_STRING);
    if (!filter_var($codcliente, FILTER_VALIDATE_INT) !== false and strlen($codcliente)>0)  
    { 
        //echo "error codcliente";
        return false;
    }

    $codusuario=filter_var($codusuario, FILTER_SANITIZE_STRING);
    if (!filter_var($codusuario, FILTER_VALIDATE_INT) !== false and strlen($codusuario)>0 )  
    { 
        //echo "error codusuario";
        return false;
    }

    $date = DateTime::createFromFormat( "d/m/Y", $fechaini );
    //echo "<br>--->". $date && DateTime::getLastErrors()['warning_count'] == 0 && DateTime::getLastErrors()['error_count'] == 0;
         
    $date = date_parse($fechaini);
    if (!checkdate($date['month'], $date['day'], $date['year'])) {
       // echo "error fecha ini";
        return false;
    }

    if (empty($_GET['anio'])) {
    if(strlen($fechaini)>3){
        $anio=date('Y', strtotime($fechaini));
    }else{
        $anio=date('Y');
    }
} else {
$anio=$_GET['anio'];
}
if (empty($_GET['mes'])) {
    if(strlen($fechaini)>3){
        $mes=date('m', strtotime($fechaini));
    }else{
    $mes=date('m');
    }
} else {
$mes=$_GET['mes'];
}





//Para un usuarios activos
$objusuarios = new Consultas('usuarios');
$objusuarios->Select();
if(strlen($codusuario)>0){
    $objusuarios->Where('codusuarios', $codusuario); 
}

//Descomentar las dos siguientes líneas en caso de no quere incluir administradores
$objusuarios->Where('tratamiento', '2', '!='); 
$objusuarios->Where('tratamiento', '100', '!='); 

$objusuarios->Where('estado', '0', '=');    
$objusuarios->Where('borrado', '0', '=');    
$objusuarios->Orden('codusuarios', 'ASC');

$usuarios = $objusuarios->Ejecutar();
$total_rowsusuarios=$usuarios["numfilas"];
$rowsusuarios = $usuarios["datos"];


function lastDateOfMonth($Month, $Year=-1) {
    if ($Year < 0) $Year = 0+date("Y");
    $aMonth         = mktime(0, 0, 0, $Month, 1, $Year);
    $NumOfDay       = 0+date("t", $aMonth);
    $LastDayOfMonth = mktime(0, 0, 0, $Month, $NumOfDay, $Year);
    return $LastDayOfMonth;
}

$fechaini=data_first_month_day($anio.'-'.$mes.'-01');
$fechafin=data_last_month_day($anio.'-'.$mes.'-01');


if(strlen($fechafin)> 3) {
    $detalle=" las fechas ".implota($fechaini)." y ".implota($fechafin);
} else {
    $detalle=" el mes de ".mes($mes) . " - ".$anio;
}
//$ultimo_dia="".$anio."-".$mes."-".date('t');
//echo  "....".$fechaini;

$dividefecha = explode("-", $fechaini);
$dividefecha1 = explode("-", $fechafin);
 
$fecha_previa = mktime(0, 0, (int)$dividefecha[0], (int)$dividefecha[1], (int)$dividefecha[2]); //Convertimos $fecha_desde en formato timestamp
$fecha_hasta = mktime(0, 0, $dividefecha1[0], $dividefecha1[1], $dividefecha1[2]); //Convertimos $fecha_hasta en formato timestamp
 
$segundos = $fecha_previa - $fecha_hasta; // Obtenemos los segundos entre esas dos fechas
$segundos = abs($segundos); //en caso de errores
 
$semanas = floor($segundos / 604800); //Obtenemos las semanas entre esas fechas.

if($semanas==0) { 
    $semanas=1;
}

$file=$_GET['file'];


if (PHP_SAPI == 'cli')die('Solo desde un navegador');

function getNameFromNumber($num) {
    $numeric = ($num - 1) % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval(($num - 1) / 26);
    if ($num2 > 0) {
        return getNameFromNumber($num2) . $letter;
    } else {
        return $letter;
    }
}

require __DIR__ . "/../../excel/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$documento = new Spreadsheet();
$documento
    ->getProperties()
    ->setCreator($ShowName)
    ->setLastModifiedBy($ShowName) // última vez modificado por
    ->setTitle('UYCODEKA')
    ->setSubject('Detalles de horas realizadas')
    ->setDescription('Este documento fue generado el día '.date("d") . " del " . date("m") . " de " . date("Y") .' a las '.date("G").':'.date("H"))
    ->setKeywords('Horas realizadas')
    ->setCategory('UYCODEKA');

$hoja = $documento->getActiveSheet();
$hoja->setTitle("Detalles de horas");


$celda="H";

$hoja->getStyle("A1:".$celda."1")->getFont()->setName('Arial')->setSize(22);
$hoja->getStyle("A1:".$celda."1")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
$hoja->setCellValue('A1', $razonsocial);
$hoja->mergeCells('A1:'.$celda."1");
$hoja->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$hoja->getRowDimension('1')->setRowHeight(30);
$hoja->getStyle("A3")->getFont()->setName('Arial')->setSize(10);
$hoja->setCellValue('A3', "Dirección: ". $direccion);
$hoja->mergeCells('A3:'.$celda."3");
$hoja->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

$hoja->getStyle("A4")->getFont()->setName('Arial')->setSize(10);
$hoja->setCellValue('A4', "Teléfono/s ". $telefono." - ". $fax);
$hoja->mergeCells('A4:'.$celda."4");
$hoja->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

$hoja->getStyle("A5")->getFont()->setName('Arial')->setSize(10);
$hoja->setCellValue('A5', "email:".$email);
$hoja->mergeCells('A5:'.$celda."5");
$hoja->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

$hoja->getStyle("A6")->getFont()->setName('Arial')->setSize(10);
$hoja->setCellValue('A6', "Web: ". $web);
$hoja->mergeCells('A6:'.$celda."6");
$hoja->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


$x=9;

$hoja->getStyle("A7:".$celda."7")->getFont()->setName('Arial')->setSize(9);
$hoja->getStyle("A7:".$celda."7")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

$hoja->setCellValue('A7',  $titulo.$detalle);
$hoja->mergeCells('A7:'.$celda."7");
$hoja->getStyle('A7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

$hoja->getRowDimension('7')->setRowHeight(14);

$col=2;
$total_usuario_proyecto=array();
$total_usuario="";

$total=0;
$horasTotales_fila='00:00';
$totalhoras_celda='00:00';
$sumaHorasTotal='00:00';
    /*Para todos los usuarios*/
    
if($total_rowsusuarios>0){
	foreach($rowsusuarios as $row_usuario){
        $total_usuario_proyecto[$row_usuario['codusuarios']]='00:00';
        $hoja->getStyle($col, $x)->getFont()->setName('Arial')->setSize(11);
        $hoja->setCellValueByColumnAndRow($col, $x, $row_usuario['nombre']." ".$row_usuario['apellido']);
        $hojaCoord=$hoja->getCellByColumnAndRow($col, $x);
        $mCell = $hojaCoord->getCoordinate();
        $cellRange = $mCell . ':' . $mCell;

        $hoja->getColumnDimension(getNameFromNumber($col))->setAutoSize(true);
        $hoja->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $col++;
	}
}

$hoja->getStyle($col, $x)->getFont()->setName('Arial')->setSize(11);	
$hoja->setCellValueByColumnAndRow($col, $x, 'Total');
$col++;
$hoja->getStyle($col, $x)->getFont()->setName('Arial')->setSize(11);	
$hoja->setCellValueByColumnAndRow($col, $x, 'Asig.');

$hoja->getRowDimension($x)->setRowHeight(20);

$x++;
$num=1;

$col=1;
$total_proyecto=array();

//Recorro todos los clientes con service activo
$objclientes = new Consultas('clientes');

$objclientes->Select();
if(strlen($codcliente)>'0'){
    $objclientes->Where('codcliente', $codcliente);
}
//$objclientes->Where('borrado', '0');    
$objclientes->Where('service', '2');    

$clientes = $objclientes->Ejecutar();
$total_rowsclientes=$clientes["numfilas"];
$rowsclientes = $clientes["datos"];

/*Para todos los cliente*/
$colAux=1;
if($total_rowsclientes>=0){
    $hoja->getColumnDimension(getNameFromNumber($col))->setAutoSize(true);

    foreach($rowsclientes as $rowclientes){
    $codcliente=$rowclientes['codcliente'];
    $total_proyecto[$codcliente]=0;
    $Descripcion=$rowclientes['nombre']." ".$rowclientes['apellido'];
        if($rowclientes['empresa']!='') {
            $Descripcion=$rowclientes['empresa'];
        }    

    $hoja->setCellValueByColumnAndRow($col, $x, $Descripcion);
    if($colAux==1){
        $hojaCoord=$hoja->getCellByColumnAndRow($col, $x);
        $FromCell = $hojaCoord->getCoordinate();
        $colAux++;
    }    

    $col++;
    $total='';
    if($total_rowsusuarios>0){
        foreach($rowsusuarios as $row_usuario){
            $totalhoras_celda='00:00';
        
            $objhoras = new Consultas('horas');
            $objhoras->Select();
            
            $objhoras->Where('borrado', '0');    
            $objhoras->Where('codcliente', $codcliente);    
            $objhoras->Where('codusuario', $row_usuario['codusuarios']);    
            $objhoras->Where("fecha" , $fechaini, '>=');
            $objhoras->Where("fecha" , $fechafin, '<=');

            $horas = $objhoras->Ejecutar();
            $total_horas=$horas["numfilas"];
            $rowshoras = $horas["datos"];
            // check if more than 0 record found
            if($total_horas>0){
                foreach($rowshoras as $rowhoras){
                    $parcial=$rowhoras["horas"].':00';
                    if($rowhoras["horas"]!='0:00') {
                        $totalhoras_celda=SumaHoras($parcial,$totalhoras_celda);
                        $horasTotales_fila=SumaHoras($horasTotales_fila,$parcial);
                    }
                }
                if($totalhoras_celda!='00:00') {
                    $total_usuario_proyecto[$row_usuario['codusuarios']]=SumaHoras($total_usuario_proyecto[$row_usuario['codusuarios']],$totalhoras_celda);
                    $sumaHorasTotal=SumaHoras($totalhoras_celda,$sumaHorasTotal);

                    $total= round(str_replace(",",".",time2seconds($totalhoras_celda)/60/60), 2);
                }
                $hoja->setCellValueByColumnAndRow($col, $x, $total);
                $total='';
            } else {
                $hoja->setCellValueByColumnAndRow($col, $x, " ");	
            }
        $total_usuario="";
        $col++;
        }
    }

        $horasTotales_fila=$horasTotales_fila.":00";
        $total_proyecto[$codcliente]=round(str_replace(",",".",time2seconds($horasTotales_fila)/60/60), 2);

        if($totalhoras_celda!='00:00') {
            $total= round(str_replace(",",".",time2seconds($horasTotales_fila)/60/60), 2);
        }
        $hoja->setCellValueByColumnAndRow($col, $x, $total);
        $total='';

        /* Calcular la direfencia de horas asignadas/realizadas y cambiar el color de fondo*/	

        $diferencia=$total_proyecto[$codcliente]-(int)$rowclientes['horas'];//*$semanas;
        
        if($diferencia<>2) {

        $lastCellValue = $hoja->getCellByColumnAndRow($col, $x);
        $cellRange = $mCell . ':' . $mCell;

            $cellRange = $lastCellValue->getCoordinate();
            if($diferencia >2) {
                $DifColor=sprintf("%02x%02x%02x", 255, 3*$diferencia,0);
                $hoja->getStyle($cellRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($DifColor);            
            } elseif($diferencia<-5) {
                $DifColor=sprintf("%02x%02x%02x", 255+ (3*$diferencia),255 ,0);
                $hoja->getStyle($cellRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($DifColor);            
            }   
        }
            
        
        $hoja->setCellValueByColumnAndRow($col, $x, $total_proyecto[$codcliente]);
        $col++;
        $hoja->setCellValueByColumnAndRow($col, $x, (int)$rowclientes['horas']);
        

        $hojaCoord=$hoja->getCellByColumnAndRow($col, $x);

        $x++;
        $num++;
        $col=1;
        $horasTotales_fila='00:00';
        $totalhoras_celda='00:00';
    }
}//Fin recorido clientes

$toCell = $hojaCoord->getCoordinate();
$cellRange = $FromCell . ':' . $toCell;

$hoja->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$hoja->getStyle($cellRange)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);



$col=2;
if($total_rowsusuarios>=0){
	foreach($rowsusuarios as $row_usuario){
	$array = explode(":", $total_usuario_proyecto[$row_usuario['codusuarios']]);
	$total_usuario1=($array[0]*60+$array[1])/60;
	$total_usuario_proyecto[$row_usuario['codusuarios']]=number_format($total_usuario1, 2, '.', ' ');

    $hoja->getStyle($col, $x)->getFont()->setName('Arial')->setSize(9);			
    $hoja->setCellValueByColumnAndRow($col, $x, $total_usuario_proyecto[$row_usuario['codusuarios']]);

$col++;
    }
}
$sumaHorasTotal=$sumaHorasTotal.":00";
$sumaHorasTotal=round(str_replace(",",".",time2seconds($sumaHorasTotal)/60/60), 2);
$hoja->getStyle($col, $x)->getFont()->setName('Arial')->setSize(9);	

$hoja->setCellValueByColumnAndRow($col, $x, $sumaHorasTotal);


$hojaCoord=$hoja->getCellByColumnAndRow($col, $x);
$toCell = $hojaCoord->getCoordinate();
$hojaCoord=$hoja->getCellByColumnAndRow(2, $x);
$FromCell = $hojaCoord->getCoordinate();

$cellRange = $FromCell . ':' . $toCell;

$hoja->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$hoja->getStyle($cellRange)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);


$writer = new Xlsx($documento);

//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$file.'".xlsx"');
header('Cache-Control: max-age=0');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 25 Agu 1825 15:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0




$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, 'Xlsx');
//$writer->save('php://output');

$writer->save('../../tmp/'.$file.'.xlsx');
//$writer->save('php://output');
exit;

?>