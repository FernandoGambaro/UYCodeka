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
 
/*
Include the session class. Modify path according to where you put the class
file.
*/

?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../common/timeout.js"></script>

		<script src="../calendario/jscal2.js"></script>
		<script src="../calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />		
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>

<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
	

<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Formularios ";
var tst='';
var alto=parent.document.getElementById("alto").value-160;

	var codfeedback='';

function OpenLeft(noteId,xwidth=450,xheight=250){
	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:xwidth, height:xheight,
			onCleanup:function(){ $('#index_left').attr('src', $('#index_left').attr('src')); 
			 $('#index_right').attr('src', $('#index_right').attr('src')); }			
	});
}
function OpenRight(noteId){
	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"750", height:"550",
			onCleanup:function(){ 
			codfeedback=$('#codformulario').val();
		$("#index_right").contents().find('codformulario').val(codfeedback);
		var MyIFrame = document.getElementById("index_right");
		var MyIFrameDoc = (MyIFrame.contentWindow || MyIFrame.contentDocument);
		if (MyIFrameDoc.document) MyIFrameDoc = MyIFrameDoc.document;
		MyIFrameDoc.getElementById("form_busqueda").submit();
			 }
	});
}

</script>	
	
<script type="text/javascript">
		function setIframeHeight(iframeName) {
		  var iframeEl = document.getElementById? document.getElementById(iframeName): document.all? document.all[iframeName]: null;
		  if (iframeEl) {
		  iframeEl.style.height = "auto"; 
		  var h = alertSize();
		  var new_h = (h-55);
		  iframeEl.style.height = new_h + "px";
		  }
		}

		function alertSize() {
		  var myHeight = 0;
		  if( typeof( window.innerWidth ) == 'number' ) {
		    //Non-IE
		    myHeight = window.innerHeight;
		  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		    //IE 6+ in 'standards compliant mode'
		    myHeight = document.documentElement.clientHeight;
		  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		    //IE 4 compatible
		    myHeight = document.body.clientHeight;
		  }
		
		  //window.alert( 'Height = ' + myHeight );
		  return myHeight;
		}
</script>
<style type="text/css">
#container {
  display: flex;                  /* establish flex container */
  flex-direction: row;            /* default value; can be omitted */
  flex-wrap: nowrap;              /* default value; can be omitted */
  justify-content: space-between; /* switched from default (flex-start, see below) */
}
#container > div {
  float:left;
  bottom:0px; padding: 0px 0px 0px 0px;
}
</style>
</head>
<body onload="setIframeHeight('index_left');" onresize="setIframeHeight('index_left');">

<input id="codformulario" type="hidden" value="">

<div id="container">

<div style="width:30%; ">
<iframe src="index_left.php" name="index_left" id="index_left" title="index_left" frameborder="0" width="100%" height="100%" marginheight="0" marginwidth="0"
 scrolling="yes" align="center" allowtransparency="true"></iframe>		
</div>

<div style="width:70%; ">
<iframe src="index_right.php" name="index_right" id="index_right" title="index_right" frameborder="0" width="100%" height="100%" marginheight="0" marginwidth="0"
 scrolling="yes" align="center" allowtransparency="true"></iframe>		

</div>

</div>
</body>
</html>