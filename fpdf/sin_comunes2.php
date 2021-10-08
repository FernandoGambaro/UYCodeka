<?php
include("../conexion.php");

class PDF extends FPDF
{
/*/Cabecera de pgina*/
function Header()
{
global $title; 
global $subtitle;
/*/ Configurar las dos lineas siguientes*/

$query = "SELECT * FROM `foto` where `oid`='$id'";
$resulta = mysqli_query($GLOBALS["___mysqli_ston"], $query) or die("Error");
  if($result=mysqli_fetch_array($resulta)){		
    header("Content-type:".$result['fototype']);
    header('Content-Disposition: inline; filename="'.$result['fotoname'].'"');
    $imagen= $result['fotocontent'];			
  } else {	
		$query = "SELECT * FROM `foto` where `oid`='12'";
		$resulta = mysqli_query($GLOBALS["___mysqli_ston"], $query) or die("Error");
		  if($result=mysqli_fetch_array($resulta)){		
		    header("Content-type:".$result['fototype']);
		    header('Content-Disposition: inline; filename="'.$result['fotoname'].'"');
		    $imagen= $result['fotocontent'];			
		  }  
  }

file_put_contents('../tmp/cabezal.png', $imagen);
   /*/Logo*/
    $this->Image("../tmp/cabezal.png", 0, 0, 210);
    $this->Ln(6);	

$this->SetFillColor(255,255,255);
$this->SetFont('Arial','B',12);
$this->SetX(0);
    
$this->MultiCell(290,6,$title,0,C,0);
   
   
}

/*/Pie de pgina*/
function Footer()
{
   $this->SetFont('Arial','',6);
	$this->SetY(-22);
	$this->Cell(0,10,'UY CODEKA WEB',0,0,'L');
	$this->SetY(-22);
   $this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
	$this->SetY(-22);
   $this->Cell(0,10,'Versión 1',0,0,'R');
   $this->Ln(2);

$query = "SELECT * FROM `foto` where `oid`='$id'";
$resulta = mysqli_query($GLOBALS["___mysqli_ston"], $query) or die("Error");
  if($result=mysqli_fetch_array($resulta)){		
    header("Content-type:".$result['fototype']);
    header('Content-Disposition: inline; filename="'.$result['fotoname'].'"');
    $imagen= $result['fotocontent'];			
  } else {	
		$query = "SELECT * FROM `foto` where `oid`='13'";
		$resulta = mysqli_query($GLOBALS["___mysqli_ston"], $query) or die("Error");
		  if($result=mysqli_fetch_array($resulta)){		
		    header("Content-type:".$result['fototype']);
		    header('Content-Disposition: inline; filename="'.$result['fotoname'].'"');
		    $imagen= $result['fotocontent'];			
		  }  
  }

file_put_contents('../tmp/pie.png', $imagen);
   /*/Logo*/
  $this->Ln(6);
    $this->Image("../tmp/pie.png", 0,  $pdf -> h, 210);   	
}

}
?>
