<?php
date_default_timezone_set("America/Montevideo"); 

include ("../conectar.php");  
include ("../funciones/fechas.php");

    if(isset($_POST['divData'])){
    	$data=$_POST['divData'];
    }
 $id="";

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

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$web=mysqli_result($rs_datos, 0, "web");


file_put_contents('../tmp/cabezal.png', $imagen);
 
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

file_put_contents('../tmp/pie.png', $imagen);

$margin_left=20;
$margin_right=20;

$header = '
<div style="margin-right: -'.$margin_right.'mm; margin-left: -'.$margin_left.'mm; "><a href="'.$web.'" target="_blank">
<img src="../tmp/cabezal.png" name="Imagen2" align="left" width="100%" height="56" border="0"></a>
</div>
';
$footer = '
<div style="margin-right: -'.$margin_right.'mm; margin-left: -'.$margin_left.'mm; ">
<img src="../tmp/pie.png" name="Imagen1" align="left" width="100%" height="55" border="0">
</div>
';


include("mpdf.php");

$mpdf=new mPDF('c','A4','','',$margin_right,$margin_left,20,20,0,0); 

$mpdf->mirrorMargins = 0;	// Use different Odd/Even headers and footers and mirror margins


$mpdf->SetHTMLHeader($header,'',true);
$mpdf->SetHTMLHeader($header,'E',true);
$mpdf->SetHTMLFooter($footer,'',true);
$mpdf->SetHTMLFooter($footer,'E',true);


$html = '
<style>

@page {
    background: url("../presupuestos_clientes/presentacion/content_new-1.png");
    background-image-resize:6;
}
 @font-face {  
   font-family: "GillSans";  
   src: local("GillSans"), url("/ttfonts/gillsan.ttf") format("truetype"); /*non-IE*/  
}

</style>

<body>
';

$total=count($data);
$x=1;
foreach($data as $field => $value)
{
	if ($x < $total){
	$html=$html . $value."<pagebreak />";
	} else {
	$html=$html . $value;
	}
	$x++;
}


$html =$html. '
</body>
';
//echo $html;
$mpdf->WriteHTML($html);

$mpdf->Output();
exit;

?>