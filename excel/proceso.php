<?php

$file=@$_GET['file'];
?>
<html>
	<head>
		<title>Excel</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
</head>
	<body bgcolor="white">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Generando Excel&nbsp;archivo:<?php echo $file;?></div>
				<img src="../img/loading_black.gif" width="400" height="400" alt="">
				</div>
			</div>
		</div>
	
	</body>
</html>
