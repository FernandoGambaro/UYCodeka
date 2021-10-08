<?php 
include ("../conectar.php");
include ("../funciones/fechas.php");  

$codfactura=$_POST["codfactura"];
$codcliente=$_POST["codcliente"];
$importe=$_POST["importe"];
$importevale=$_POST["importevale"];
$importe=$importe-$importevale;
$numdocumento=$_POST["numdocumento"];
$fechacobro=$_POST["fechacobro"];
$fechacobro=explota($_POST["fechacobro"]);
$formapago=$_POST["formapago"];
$moneda=$_POST["moneda"];

$sel_comprueba="SELECT * FROM cobros WHERE codfactura='$codfactura'";
$rs_comprueba=mysqli_query($GLOBALS["___mysqli_ston"], $sel_comprueba);

if (mysqli_num_rows($rs_comprueba) > 0 ) {
	?>
		<script>
		alert("Esta factura ya se cobro con anterioridad");
		parent.document.getElementById("botticket").disabled=false;
		parent.document.getElementById("botaceptar").disabled=true;
		//parent.window.close()
		</script>; <?php

} else {

		$sel_insert="INSERT INTO cobros (id,codfactura,codcliente,importe,moneda,codformapago,numdocumento,fechacobro,observaciones) 
		VALUES ('','$codfactura','$codcliente','$importe','$moneda','$formapago','$numdocumento','$fechacobro','')";
		$rs_insert=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
		
		$sel_insert2="INSERT INTO librodiario (id,fecha,tipodocumento,coddocumento,codcomercial,codformapago,numpago,moneda,total) 
		VALUES ('','$fechacobro','2','$codfactura','$codcliente','$formapago','$numdocumento','$moneda','$importe')";
		$rs_insert2=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert2);
		
		$sel_insert3="UPDATE facturas SET estado=2 WHERE codfactura='$codfactura'";
		$rs_insert3=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert3);
		
		?>
		<script>
		alert("El cobro se ha efectuado correctamente");
		parent.document.getElementById("botticket").disabled=false;
		parent.document.getElementById("botaceptar").disabled=true;
		parent.$('idOfDomElement').colorbox.close();
		//parent.window.close()
		</script>;
		
<?php } ?>
