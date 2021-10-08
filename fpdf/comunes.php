<?php
session_start();
require_once('../class/class_session.php');
/*
Instantiate a new session object. If session exists, it will be restored,
otherwise, a new session will be created--placing a sid cookie on the user's
computer.
*/
if (!$s = new session()) {
  /*
  There is a problem with the session! The class has a 'log' property that
  contains a log of events. This log is useful for testing and debugging.
  */
  echo "<h2>Ocurrió un error al iniciar session!</h2>";
  echo $s->log;
  exit();
}

$version=@$s->data['version'];

$UserNom=@$s->data['UserNom'];
$UserApe=@$s->data['UserApe'];
$ShowName=@$UserNom. " " .$UserApe;


class PDF extends FPDF
{
/*/Cabecera de pgina*/
function Header()
{
global $title; 
global $subtitle;
global $codcliente;	
global $ww;
global $header;
global $id;
global $horasservice;
global $meses;


/*/ Configurar las dos lineas siguientes*/

$query = "SELECT * FROM `foto` where `oid`='$id'";
$resulta = mysqli_query($GLOBALS["___mysqli_ston"], $query);
  if($result=mysqli_fetch_array($resulta)){		
    header("Content-type:".$result['fototype']);
    header('Content-Disposition: inline; filename="'.$result['fotoname'].'"');
    $imagen= $result['fotocontent'];			
  } else {	
		$query = "SELECT * FROM `foto` where `oid`='12'";
		$resulta = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		  if($result=mysqli_fetch_array($resulta)){		
		    header("Content-type:".$result['fototype']);
		    header('Content-Disposition: inline; filename="'.$result['fotoname'].'"');
		    $imagen= $result['fotocontent'];			
		    $extencion= explode('.', $result['fotoname']);			
		  }  
  }

$sql_datos="SELECT web FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$web=mysqli_result($rs_datos, 0, "web");

$height=$this -> h;
$wide=$this -> w;

file_put_contents('../tmp/cabezal.png', $imagen);
   /*/Logo*/
    $this->Image("../tmp/cabezal.png", 0, 0, $wide, 15, $extencion[1], $web);
    $this->Ln(8);


//Nombre del Listado
$this->SetFillColor(255,255,255);
$this->SetFont('Arial','B',16);
$this->SetY(20);
$this->SetX(0);
$this->MultiCell(200,6,$title,0,'C',0);

/*/Buscamos y listamos los equipos del cliente*/
if($subtitle!='') {
//$this->SetFillColor(255,255,255);
$this->SetFont('Arial','B',12);

$this->SetFillColor(200,200,200);
$this->SetTextColor(0);
$this->SetDrawColor(0,0,0);
$this->SetLineWidth(.1);

$this->Ln();
$this->SetFont('Arial','B',11);
$this->MultiCell(142,5,$subtitle,0,'C',0,0);
}
/*/Restauracin de colores y fuentes*/
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','B',7);

/*/Colores, ancho de línea y fuente en negrita*/
$this->SetFillColor(200,200,200);
$this->SetTextColor(0);
$this->SetDrawColor(0,0,0);
$this->SetLineWidth(.2);
$this->SetFont('Arial','B',8);

$this->Ln();
/*/Cabecera*/
for($i=0;$i<count($header);$i++)
	$this->Cell($ww[$i],6,$header[$i],1,0,'C',1);
$this->Ln();
    
}

/*/Pie de pgina*/
function Footer()
{
	$largo=0;
global $id;
global $version;
global $ShowName;

   $this->SetFont('Arial','',6);
	$this->SetY(-23);
	$this->Cell(0,10,'UY CODEKA WEB - Versión '. $version ,0,0,'L');
	$this->SetY(-23);
   $this->Cell(0,10,' Pagina '.$this->PageNo(),0,0,'C');
	$this->SetY(-23);
   $this->Cell(0,10,'Impreso por: '.$ShowName,0,0,'R');
   $this->Ln(2);
   
$query = "SELECT * FROM `foto` where `oid`='$id'";
$resulta = mysqli_query($GLOBALS["___mysqli_ston"], $query);
  if($result=mysqli_fetch_array($resulta)){		
    header("Content-type:".$result['fototype']);
    header('Content-Disposition: inline; filename="'.$result['fotoname'].'"');
    $imagen= $result['fotocontent'];			
  } else {	
		$query = "SELECT * FROM `foto` where `oid`='13'";
		$resulta = mysqli_query($GLOBALS["___mysqli_ston"], $query);
		  if($result=mysqli_fetch_array($resulta)){		
		    header("Content-type:".$result['fototype']);
		    header('Content-Disposition: inline; filename="'.$result['fotoname'].'"');
		    $imagen= $result['fotocontent'];			
		  }  
  }
$largo=($this -> h) -13;
$ancho=$this ->w;

file_put_contents('../tmp/pie.png', $imagen);
   /*/Logo*/
  $this->Image("../tmp/pie.png", 0, $largo, $ancho, 13);    
   	
}

}
?>
