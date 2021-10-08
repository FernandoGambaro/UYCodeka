<?php

$file=@$_GET['file'];
$texto=@$_GET['texto'];
?>
<html>
	<head>
		<title>Procesando</title>
		<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
</head>
	<body bgcolor="white">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $texto;?> <?php echo $file;?></div>
				<img src="../img/sending.gif" width="200" height="100" alt="">
				</div>
			</div>
		</div>
	
	</body>
</html>
