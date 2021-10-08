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
 
session_start();
require_once('../../class/class_session.php');
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


if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
	/*/user is not logged in*/
	echo "<script>location.href='../../index.php'; </script>";
   //header("Location:../index.php");	

} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
  		//header("Location:../index.php");	
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];
 $documento='';
 $importe='';
 
include ("../../conectar.php");
include("../../common/verificopermisos.php");
//include("../common/funcionesvarias.php");

include ("../../funciones/fechas.php");

//header('Content-Type: text/html; charset=UTF-8'); 

$codpresupuesto=$_GET['codpresupuesto'];

$consulta = "Select * from presupuestos,clientes where presupuestos.codpresupuesto='$codpresupuesto' and presupuestos.codcliente=clientes.codcliente";
$resultado = mysqli_query( $conexion, $consulta);
$lafila=mysqli_fetch_array($resultado);

$codcliente=$lafila["codcliente"]; 
$fecha=$lafila["fecha"]; 


$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 
$fecha= $dias[date('w',strtotime($fecha))]." ". date('d',strtotime($fecha))." de ".$meses[date('n',strtotime($fecha))-1]. " del ".date('Y',strtotime($fecha)) ;


    
$query="SELECT * FROM clientes WHERE codcliente='$codcliente'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

$nombre=mysqli_result($rs_query, 0, "nombre");
$apellido=mysqli_result($rs_query, 0, "apellido");
$empresa=mysqli_result($rs_query, 0, "empresa");
$uemail=mysqli_result($rs_query, 0, "email");
   

 $text = array("[empresa]", "[nombre]", "[apellido]", "[usuario]", "[documento]", "[fecha]"); 
 $replace = array($empresa, $nombre, $apellido, $UserNom, $documento, $fecha);
 

    $consulta = "SELECT * FROM elementos ORDER BY orden ASC";
    $resultado = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
    $elementos = null;
    while ($datos = mysqli_fetch_assoc($resultado)){
        $elementos[$datos['codelemento']] = str_replace($text, $replace, $datos['texto']);
        $element[$datos['codelemento']] = $datos['descripcion'];
    }

?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
 
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="ckeditor/adapters/jquery.js"></script>
<link href="ckeditor/contents.css" media="all" rel="stylesheet">

<!-- Importamos libreria jQuery --!>
<script src="../js/jquery.min.js"></script>
<!-- Plugin para dar mas efecto a nuestro scroll -->
<script src="../js/jquery.easing.min.js"></script>

<script type="text/javascript">
var newItemHide='';
var newItemShow='';
var newItemHideN='';
var newItemShowN='';
var newItemInput='';
var newItemInputId='';
var newItemInputValue='';
var newItemCombo='';
var newItemComboN='';
var trigerclass='';

var initials ={};

$(document).ready( function(){
	
  $('.trigger').click( function(e){
		$('#suggestions').hide();
		$('#suggestionslong').hide();
      if (newItemShowN!='') {
      $(newItemShowN).hide();
      $(newItemHideN).show();
      }
		trigerclass='';
      newItemHide=this.id+'Hide';
      newItemShow=this.id+'Show';
      newItemHideN='#'+this.id+'Hide';
      newItemShowN='#'+this.id+'Show';
      newItemInput=this.id+'Input';
      newItemInputId='#'+this.id+'Input';
      newItemInputValue=this.id+'Value';
      newItemCombo=this.id+'Combo';
      newItemComboN='#'+this.id+'Combo';

      p = $(this);
      var offset = p.offset();
      var x = offset.left - this.offsetLeft - 290 ;
      if (newItemShow=='newItemFShow') {
      var x = offset.left - this.offsetLeft - 290 ;
      var y = offset.top - this.offsetTop - 200;
      } else {
      var y = offset.top - this.offsetTop -100;
      }
      document.getElementById(newItemShow).style.left = x ;
      document.getElementById(newItemShow).style.top = y - 10;

      $(newItemShowN).show(); $(newItemHideN).hide();
      	   
      e.preventDefault();
   } );//Finaliza trigger

  $('.triggerClose').click( function(e){


      $("#newItemESShow").hide();
//      $(newItemHideN).show();
		$('#suggestions').hide();
		$('#suggestionslong').hide();

      trigerclass='';
      e.preventDefault();
   } );/*/Finaliza triggerClose*/
   

$('.simpleinput').click( function(e){
	$(newItemShowN).hide(); $(newItemHideN).show();
	$('#suggestions').hide();
	$('#suggestionslong').hide();
});

});
</script>

<script type="text/javascript">
var count=100;

$(document).ready( function(){

	$('.remove').click( function (e) {
		var remove='#trseccion'+this.id;
		var remo='#elemento-'+this.id;
		$(remove).remove();
		$(remo).remove();
		//alert(this.id);
		e.preventDefault();
	});	
	
  $('.add-section-button').click( function(e){

   var txt1 ='<li id="elemento-'+count+'" class=""><a href="#seccion'+count+'" id="textelemento-'+count+'">Nuevo</a></li>';
	var txt2='<tr id="trseccion'+count+'"><td><input name="descripcion'+count+'" id="descripcion-'+count+'" value="Nuevo" maxlength="50" size="50" style="border: medium #85b9e9 double;" onkeyUp="document.getElementById(\'textelemento-'+count+'\').innerHTML = this.value"></input> <section id="seccion'+count+'" class="item"><a href="#"><div class="item remove" id="'+count+'" style="z-index:1000;"></div></a><div class="tab-pane" > <a name="seccion'+count+'"></a>	<div style=" padding: 10mm 12mm 16mm 10mm;">			<div class="hiddenFormWrap"> <textarea id="DatauseData'+count+'" name="divData['+count+']" title="savedData" class="hiddenTextArea" ></textarea></div> <div contenteditable="true" id="useData'+count+'" class="editor" style="z-index:100;">&nbsp;Nuevo&nbsp;</div></div></div></section></td></tr>';    
    
    $(".nav").append(txt1);
    $("#editable").append(txt2);

 for (var instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].destroy();
    }
    CKEDITOR.inlineAll();
     count=count+1; 	   
      //e.preventDefault();
   });//Finaliza trigger
   
    $('.add-section-button-pre').click( function(e){
    	$('#newItemESShow').show();
		//e.preventDefault();
		}); 
});


function lookup(inputString) {
	if(inputString.length == 0) {
		$('#suggestionslong').hide();
	} else {
		var empresa="<?php echo $empresa;?>";
		var nombre="<?php echo $nombre;?>";
		var apellido="<?php echo $apellido;?>";
		var usuario="<?php echo $UserNom;?>";
		var documento="<?php echo $documento;?>";
		var fecha="<?php echo $fecha;?>";
		$.post("busco.php?empresa="+empresa+ "&nombre="+nombre+"&apellido"+apellido+"&usuario="+usuario+"&documento="+documento+"&fecha="+fecha, {queryString: ""+inputString+""}, function(data){
			if(data.length >0) {
				$('#suggestionslong').show();
				$('#autoSuggestionsListlong').html(data);
			}
		});
	}
} /*/ lookup*/

function fill(thisValue) {

   var item0=thisValue.split("-")[0];
   var item1=thisValue.split("-")[1];

	var cadena=item1.split(',');
	var res = '';
	
	for (var i=0; i<cadena.length; i++) {
		dato=cadena[i];
		res =res + String.fromCharCode(dato);
	}   
   var txt1 ='<li id="elemento-'+count+'" class=""><a href="#seccion'+count+'" id="textelemento-'+count+'">'+item0+'</a></li>';
	var txt2='<tr id="trseccion'+count+'"><td><input name="descripcion'+count+'" id="descripcion-'+count+'" value="'+item0+'" maxlength="50" size="50" style="border: medium #85b9e9 double;"		onkeyUp="document.getElementById(\'textelemento-'+count+'\').innerHTML = this.value"> </input><section id="seccion'+count+'" class="item"><a href="#"><div class="item remove" id="'+count+'" style="z-index:1000;"></div></a> <div class="tab-pane" ><a name="seccion'+count+'"></a><div style=" padding: 10mm 12mm 16mm 10mm;">	 			<div class="hiddenFormWrap">		<textarea id="DatauseData'+count+'" name="divData['+count+']" title="savedData" class="hiddenTextArea" >	 			</textarea>		</div><div contenteditable="true" id="useData'+count+'" class="editor" style="z-index:100;">'+res+'</div></div></div></section></td></tr>';    
    
    $(".nav").append(txt1);
    $("#editable").append(txt2);

	 for (var instance in CKEDITOR.instances) {
	        CKEDITOR.instances[instance].destroy();
	    }
	    CKEDITOR.inlineAll();
	     count=count+1;
	$('#newItemESShow').hide();
	setTimeout("$('#suggestionslong').hide();", 200); 	   	                 
}/*/End fill*/   
	



</script>

<script type="text/javascript">
function doSaveData(){
var sourceData='';
$(".editor").each(function(index, value) {
   sourceData = document.getElementById($(this).attr('id')).innerHTML;
	var nuevo="Data"+$(this).attr('id');
	document.getElementById(nuevo).value = sourceData;
	//alert(sourceData);
});

	return true;
}	
</script>


<style type="text/css">

body {
    color: #333333;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 13px;
    line-height: 18px;
}

.tab-pane {
  width: 210mm;
  
  width: 215.9mm;
  
  margin: 0;
  padding: 0;
}

.tab-pane {
  background-color: #ffffff;
  background-image: url('content_new-1.png');
  background-repeat: no-repeat;
  /*background-repeat: repeat-y;*/

  /*height: 297mm;*/
  /*min-height: 297mm;
  
  min-height: 279.4mm;
  */
  /* letter 215.9 mm Ã— 279.4 mm */

  display: inline-block;

  background-size: 210mm 297mm;
  
  background-size: 215.9mm 279.4mm;
  
}

.tab-content{
  content:"";
  /*position:absolute;*/
  z-index:-10;
  bottom:0px;
  right:0px;
  height: 8.5cm;

  width: 210mm;
  background-size: 26cm 3cm, 210mm 297mm;
  
  width: 8.5in;
  background-size: 26cm 3cm, 8.5in 11in;
  background-image: url("gradient-from-top.png"), url("content_theme-14.png");
  background-position: center 0cm, center bottom;
  background-repeat: no-repeat;
}
.tab-content p{
  text-align: justify;
}
.cool-area-save {
    height: 20px;
    /*position: absolute;*/
    right: 0;
    top: 0;
    width: 20px;
    z-index: 1005;
    cursor: pointer;
    /*display: block; */ 
    right: 50px;  
}
.icon-hdd {
    background-position: 0 -144px;
}
.icon {
    background-image: url("glyphicons-halflings.png");
    background-position: 14px 14px;
    background-repeat: no-repeat;
    display: inline-block;
    height: 14px;
    line-height: 14px;
    margin-top: 1px;
    vertical-align: text-top;
    width: 14px;
}
.hiddenFormWrap{display: none;}
 @font-face {  
   font-family: "GillSans";  
   src: local("GillSans"), url("../../../mpdf60/ttfonts/gillsan.ttf") format("truetype"); /*non-IE*/  
}
.proposal-buttons-edit-container .add-section-button {
    margin-left: 0;
}
.btn-flat {
    background-color: #e4e4e4;
    border: 1px solid #cfcfcf;
    border-radius: 1px;
    color: #454545;
    display: inline-block;
    float: left;
    font-size: 10px;
    height: 48px;
    line-height: 12px;
    margin-bottom: 5px;
    margin-left: 5px;
    margin-top: 0;
    padding-left: 8px;
    padding-right: 8px;
    padding-top: 5px;
    text-align: center;
    text-transform: uppercase;
    width: 99px;
}
 .proposal-buttons-edit-container a {
    margin-top: 0;
}
html *, html {
}
a, a:focus, .btn, .btn:focus {
    outline: 0 none;
}
a {
    color: #2165af;
    text-decoration: none;
}

.nav-tabs li > a {
    background-image: url("li-style2.png");
    background-position: left top;
    background-repeat: repeat-y;
    color: #3a3a3a;
}

.nav-tabs > li > a {
    border: 1px solid transparent;
    border-radius: 4px 4px 0 0;
    line-height: 18px;
    padding-bottom: 8px;
    padding-top: 8px;
}
.nav-tabs .active > a, .nav-tabs .active > a:hover, .nav-tabs li > a:hover {
    background-image: url("li-style2-active.png");
    background-position: left top;
    background-repeat: repeat-y;
}
.nav-tabs li > a {
    /*background: none repeat scroll 0 0 rgba(0, 0, 0, 0);*/
    border: medium none;
    border-radius: 0;
    font-family: Arial,sans-serif;
    font-size: 13px;
    font-weight: normal;
    line-height: 20px;
    margin-bottom: 0;
    margin-right: 0;
    min-width: 130px;
    padding-left: 25px;
    padding-right: 0;
}
.nav-tabs > .active > a, .nav-tabs > .active > a:hover {
    cursor: pointer;
}
.nav-tabs > .active > a, .nav-tabs > .active > a:hover {
    background-color: #f9f9f9;
}
.nav-tabs > .active > a, .nav-tabs > .active > a:hover, .nav-tabs li > a:hover {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: white;
    border-color: #ddd #ddd transparent;
    border-image: none;
    border-style: solid;
    border-width: 0px;
    color: #555555;
    cursor: default;
}
.nav-tabs > li > a {
    border: 1px solid transparent;
    border-radius: 4px 4px 0 0;
    line-height: 18px;
    padding-bottom: 8px;
    padding-top: 8px;
}
.nav > li > a {
    display: block;
}
html *, html {
}
a, a:focus, .btn, .btn:focus {
    outline: 0 none;
}
a {
    color: #2165af;
    text-decoration: none;
}
 .proposal-buttons-edit-container .btn {
    font-size: 11px;
}
 .proposal-buttons-edit-container a {
    margin-top: 0;
}
.proposal-preview-container .preview-button {
    width: 99px;
}
.proposal-preview-container .btn {
    margin-bottom: 7px;
    margin-right: 15px;
    margin-top: 7px;
}
.btn-primary {
    background-color: #007fbb;
    background-image: none;
    border-color: #007fbb;
}
.btn {
    border-radius: 0;
    box-shadow: none;
    padding: 4px 10px;
    text-shadow: none;
}
a, a:focus, .btn, .btn:focus {
    outline: 0 none;
}
.btn-primary {
    background-color: #1b7fb0;
    background-image: linear-gradient(to bottom, #1d84b7, #1a77a5);
    background-repeat: repeat-x;
    border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
    color: white;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
}
.btn {
    border-color: rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.25);
}
.btn {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #f6f6f6;
    background-image: linear-gradient(to bottom, #fff, #e8e8e8);
    background-repeat: repeat-x;
    border-color: #bbbbbb #bbbbbb #a2a2a2;
    border-image: none;
    border-radius: 4px;
    border-style: solid;
    border-width: 1px;
    box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
    color: #333333;
    cursor: pointer;
    display: inline-block;
    font-size: 13px;
    line-height: 18px;
    margin-bottom: 0;
    padding: 4px 12px;
    text-align: center;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
    vertical-align: middle;
}
html *, html {
}
a, a:focus, .btn, .btn:focus {
    outline: 0 none;
}
a {
    color: #2165af;
    text-decoration: none;
}

.scroll-tabs {
    left: 0;
    overflow-x: hidden;
    position: static;
    transition: left 0.5s ease 0s, opacity 0.5s ease 0s, visibility 0.5s ease 0s;
    width: 250px;
}

.tabbable:before, .tabbable:after {
    content: "";
    display: table;
    line-height: 0;
}
.tabbable:after {
    clear: both;
}
.tabbable:before, .tabbable:after {
    content: "";
    display: table;
    line-height: 0;
}

.cool-section-delete {
    right: 5px;
    top: 9px;
}
.cool-section-delete {
    display: none;
    height: 20px;
    position: absolute;
    right: 0;
    top: 0;
    width: 20px;
    z-index: 1005;
}

.icon-white, .nav-pills > .active > a > [class^="icon-"],
 .nav-pills > .active > a > [class*=" icon-"],
  .nav-list > .active > a > [class^="icon-"],
   .nav-list > .active > a > [class*=" icon-"],
    .navbar-inverse .nav > .active > a > [class^="icon-"],
     .navbar-inverse .nav > .active > a > [class*=" icon-"],
      .dropdown-menu > li > a:hover > [class^="icon-"],
       .dropdown-menu > li > a:hover > [class*=" icon-"],
        .dropdown-menu > .active > a > [class^="icon-"],
         .dropdown-menu > .active > a > [class*=" icon-"],
          .dropdown-submenu:hover > a > [class^="icon-"],
           .dropdown-submenu:hover > a > [class*=" icon-"] {
    background-image: url("glyphicons-halflings-white.png");
}


.item {

    position: relative;
}
.item:after {
	background-image: url("glyphicons-halflings-white.png");
	 background-repeat: no-repeat;
    content:"";
    width: 14px;
    height: 14px;
    top: 0;
    right: 0;
    position:absolute;
background-position: -312px 0;
} 

/* if you want the hover effect */
.item:hover:after {
    content:"";
    background-image: url("glyphicons-halflings.png");
}
[class^="icon-"], [class*=" icon-"] {
    display: inline-block;
    width: 14px;
    height: 14px;
    line-height: 14px;
    vertical-align: text-top;
    background-image: url('glyphicons-halflings.png');
    background-position: 14px 14px;
    background-repeat: no-repeat;
    margin-top: 1px;
}
.icon-remove {
    background-position: -312px 0px;
}
.nav-tabs .cool-drag-handler {
    right: -30px;
}
.cool-section-delete, .nav-tabs .cool-drag-handler {
    right: 5px;
    top: 9px;
}
.cool-drag-handler {
    right: 25px;
}
.cool-section-delete, .cool-area-save, .cool-area-delete, .cool-drag-handler {
    /*display: none;*/
    height: 20px;
    position: absolute;
    right: 0;
    top: 0;
    width: 20px;
    z-index: 105;
}
.inner-slide .proposal-section .price-table {
    margin-bottom: 0;
    margin-top: 0;
    width: 100%;
}
.proposal-area.ui-sortable-helper div, .proposal-area.ui-sortable-helper p, .proposal-area.ui-sortable-helper table, .proposal-area.ui-sortable-helper span, .proposal-area.ui-sortable-helper ul, .proposal-area.ui-sortable-helper ol, .proposal-preview-container .tab-content div, .proposal-preview-container .tab-content p, .proposal-preview-container .tab-content table, .proposal-preview-container .tab-content span, .proposal-preview-container .tab-content ul, .proposal-preview-container .tab-content ol {
    color: #000000;
    font-size: 10pt;
}
.proposal-area.ui-sortable-helper p, .proposal-area.ui-sortable-helper li, .proposal-area.ui-sortable-helper table, .proposal-area.ui-sortable-helper div, .proposal-area.ui-sortable-helper span, .proposal-html .proposal-content p, .proposal-html .proposal-content li, .proposal-html .proposal-content table, .proposal-html .proposal-content div, .proposal-html .proposal-content span, .pdf-html .proposal-section p, .pdf-html .proposal-section li, .pdf-html .proposal-section table, .pdf-html .proposal-section div, .pdf-html .proposal-section span {
    line-height: 1.3;
}
.price-table {
    margin-bottom: 0;
    margin-top: 0;
    width: 100%;
}
.price-table thead:first-child tr:first-child th:first-child, .price-table tbody:first-child tr:first-child td:first-child {
    border-top-left-radius: 4px;
}
.price-table caption + thead tr:first-child th, .price-table caption + tbody tr:first-child th, .price-table caption + tbody tr:first-child td, .price-table colgroup + thead tr:first-child th, .price-table colgroup + tbody tr:first-child th, .price-table colgroup + tbody tr:first-child td, .price-table thead:first-child tr:first-child th, .price-table tbody:first-child tr:first-child th, .price-table tbody:first-child tr:first-child td {
    border-top: 0 none;
}
.price-table .price, .price-table .total, .price-table .total-vat {
    width: 92px;
}
.price-table th {
    font-weight: bold;
}
.price-table thead:first-child tr:first-child th:last-child, .price-table tbody:first-child tr:first-child td:last-child {
    border-top-right-radius: 4px;
}
.price-table caption + thead tr:first-child th, .price-table caption + tbody tr:first-child th, .price-table caption + tbody tr:first-child td, .price-table colgroup + thead tr:first-child th, .price-table colgroup + tbody tr:first-child th, .price-table colgroup + tbody tr:first-child td, .price-table thead:first-child tr:first-child th, .price-table tbody:first-child tr:first-child th, .price-table tbody:first-child tr:first-child td {
    border-top: 0 none;
}
.area .area-content table th, .area .area-content table td, .inner-slide .proposal-section table th, .inner-slide .proposal-section table td {
    border-color: #dddddd;
    line-height: 18px;
    padding: 6px;
    vertical-align: top;
}
.price-table th {
    font-weight: bold;
}
</style>
</head>
<body>
<form name="form1" action="../../mpdf60/imprimir_presupuesto.php" method="post" onsubmit="return doSaveData();">

<table>
<tr>
<td valign="top"><table><tr><td width="250px">
  <div style="position: fixed; top:50px;">
  <div class="proposal-buttons-edit-container">
    <div class="center">

      <div style="width: 100%;">
      <div style="min-height: 51px;">

      <a class="btn-flat add-section-button" ><span>Agregar seccio&oacuten en blanco</span></a>
      <a class="btn-flat add-section-button-pre" ><span>Agregar secci&oacuten predefinida</span></a>

      </div>
      </div>

      <div id="newItemESShow" style="display: none; position:absolute; border: 0px solid #000; z-index:10;">
         <div class="seleccione">Seleccione uno<div style="position:absolute; right:10px; top:3px"> 
         <img id="newItemES" src="../../img/minus.png" width="16" height="16" vspace="0" hspace="0" align="left" border="0" style="cursor:pointer;" class="triggerClose"></div><div>
         <input type="text" size="25" id="oidtipoestudio" onkeyup="lookup(this.value);" onblur="fill('newItemESInput',);" autocomplete="off"  class="boxinput"/>
      </div></div>
<div class="suggestionsBoxLong" id="suggestionslong" style="display: none; position:relative;">
      <div class="suggestionListLong" id="autoSuggestionsListlong" >
	       &nbsp;
      </div>
</div>      
      
      </div>      


    </div>
  </div>

  <div style="max-height: 281px; min-width: 250px; overflow-y: auto;" class="tabbable tabs-left">
      <div style="max-height: 210px; overflow-y: auto; position: absolute;" class="scroll-tabs">
      <ul class="nav nav-tabs" style="list-style-type: none;">
      
            <?php 
            $x=0;
                foreach ($element as $id => $nombre){
                	if($x==0) {
                    echo '<li class="active" id="elemento-'.$id.'" ><a href="#seccion'.$id.'">'.$nombre.'
									</a></li>';
						} else {
                    echo '<li id="elemento-'.$id.'" class=""><a href="#seccion'.$id.'">'.$nombre.'

							<div class="cool-section-delete" ><i class="icon icon-remove"></i></div>
							<div class="cool-drag-handler"><i class="icon icon-move"></i></div>                       
                          
        					</a></li>';							
						}
						$x++;
					}
            ?>
      </ul>
      </div>
	</div>


  </div> 
<div style="position: fixed; top:350px;">         
<input type="submit" value="Imprimir"></input>

<div class="cool-section-delete" ><i class="icon icon-remove"></i></div>
<div class="cool-drag-handler"><i class="icon icon-move"></i></div>   
							
</div>
      </td></tr></table></td>
      
 
<td>
<table id="editable">
<tr><td>
<p>&nbsp;
<span style="font-family:gillsans;"><strong>Formato presentaci&oacuten del presupuesto</strong></span></p>



</td></tr>

   <?php 
		foreach ($elementos as $id => $nombre){
			if($nombre!='') {
	?>
	<tr id="trseccion<?php echo $id ;?>"><td>
	<input name="descripcion<?php echo $id ;?>" id="descripcion<?php echo $id ;?>" value="<?php echo $element[$id];?>" maxlength="50" size="50"  autocomplete="off" style="border: medium #85b9e9 double;"></input>
	<section id="seccion<?php echo $id ;?>" class="item"><a href="#"><div class="item remove" id="<?php echo $id ;?>" style="z-index:1000;"></div></a>
	<div class="tab-pane" >
	<a name="seccion<?php echo $id ;?>"></a> 
		<div style=" padding: 10mm 12mm 16mm 10mm;">
		<div class="hiddenFormWrap">
			<textarea id="DatauseData<?php echo $id ;?>" name="divData[<?php echo $id ;?>]" title="savedData" class="hiddenTextArea" >&nbsp;</textarea>
		</div>
		<div contenteditable="true" id="useData<?php echo $id ;?>" class="editor" style="z-index:100;">
		<?php
		echo $nombre 
		?>
		</div>
		</div>
	</div>
	</section>			
	</td></tr>
	<?php
			} else {

?>
<tr id="trseccion<?php echo $id ;?>"><td>
	<input name="descripcion<?php echo $id ;?>" id="descripcion<?php echo $id ;?>" value="<?php echo $element[$id];?>" maxlength="50" size="50"  autocomplete="off" style="border: medium #85b9e9 double;"></input>
	<section id="seccion<?php echo $id ;?>" class="item"><a href="#"><div class="item remove" id="<?php echo $id ;?>" style="z-index:1000;"></div></a>
	<div class="tab-pane" >
	<a name="seccion<?php echo $id ;?>"></a> 
		<div style=" padding: 10mm 12mm 16mm 10mm;">
		<div class="hiddenFormWrap">
			<textarea id="DatauseData<?php echo $id ;?>" name="divData[<?php echo $id ;?>]" title="savedData" class="hiddenTextArea" >&nbsp;</textarea>
		</div>
		<div contenteditable="true" id="useData<?php echo $id ;?>" class="editor" style="z-index:100;">
		
<table width="95%" style="page-break-inside:avoid">
  <thead>
	<tr>
      <td class="name" colspan="3"><span style="font-family:gillsans,verdana,geneva,sans-serif;"><span style="font-size: 12px;">Descripci&oacute;n</span></span></td>
      <td class="quantity"><span style="font-family:gillsans,verdana,geneva,sans-serif;"><span style="font-size: 12px;">Cantidad</span></span></td>
      <td class="price"><span style="font-family:gillsans,verdana,geneva,sans-serif;"><span style="font-size: 12px;">Precio</span></span></td>
      <td class="total"><span style="font-family:gillsans,verdana,geneva,sans-serif;"><span style="font-size: 12px;">Total</span></span></td>      
   </tr>
  </thead>
  <tbody>
<?php
/* --- Segun categoría de artículos --- */
	$familia = "Select presulinea.*,familias.nombre as nombre,familias.codfamilia from presulinea,familias where codpresupuesto='$codpresupuesto' and presulinea.codfamilia=familias.codfamilia
	  order by familias.codfamilia";
    $res_familia = mysqli_query( $conexion, $familia);

	$lineas_familia=0;
while ($lineas_familia < mysqli_num_rows($res_familia))
{
	$codfamilia=mysqli_result($res_familia, $lineas_familia, "codfamilia");
	$nombre=mysqli_result($res_familia, $lineas_familia, "nombre");
	$lineas_familia++;
?>  
    <tr class="group-header">
    <td class="name" colspan="6"><span style="font-size: 12px;"><span style="font-family:gillsans,verdana,geneva,sans-serif;"><?php echo $nombre;?></span></span>
    </td>
  </tr>
<?php

/* --- Lineas de la orden de compra --- */
	
	$consulta32 = "Select * from presulinea where codpresupuesto='$codpresupuesto' and codfamilia='$codfamilia' order by numlinea";
    $resultado32 = mysqli_query( $conexion, $consulta32);

	$lineas=0;
	while ($lineas < mysqli_num_rows($resultado32))
	{
	$pos=0;

	  $codarticulo=mysqli_result($resultado32, $lineas, "codigo");
	  $codfamilia=mysqli_result($resultado32, $lineas, "codfamilia");
	  
	  $sel_articulos="SELECT * FROM articulos WHERE codarticulo='$codarticulo' AND codfamilia='$codfamilia'";
	  $rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
	  $detalles=mysqli_result($rs_articulos, 0, "descripcion")." ";


	  $importe32= number_format(mysqli_result($resultado32, $lineas, "importe"),2,",",".");	  
	  if(mysqli_result($resultado32, $lineas, "cantidad")>0) {
	  $precio32= number_format(mysqli_result($resultado32, $lineas, "importe")/mysqli_result($resultado32, $lineas, "cantidad"),2,",",".");
	  } else {
	  $precio32= number_format(mysqli_result($resultado32, $lineas, "importe"),2,",",".");
	  }

	  	  
?>
	<tr class="oddrow">
		<td class="name" colspan="3">
      <div class="name-body">
      <span class="product-name"><span style="font-size:12px;"><span style="font-family:gillsans,verdana,geneva,sans-serif;">
      <?php echo $detalles; ?>
      </span></span>
      </div>
	</td>
      <td class="quantity"><span style="font-size:12px;"><span style="font-family:gillsans,verdana,geneva,sans-serif;">
      <?php echo mysqli_result($resultado32, $lineas, "cantidad");?></span>
      </td>
      <td class="price">
        <span class="price-value"><span style="font-size:12px;"><span style="font-family:gillsans,verdana,geneva,sans-serif;">
        <?php echo $precio32;?>
        </span></span>
      </td>
      <td class="total"><span style="font-size:12px;"><span style="font-family:gillsans,verdana,geneva,sans-serif;"><?php echo $importe32;?></span></span>
      </td> 
  </tr>

<?php
	  //vamos acumulando el importe
	  $importe=$importe + mysqli_result($resultado32, $lineas, "importe");

	  $lineas=$lineas + 1;	  
	}
}
?>
  </tbody>
</table>
		</div>
		</div>
	</div>
	</section>
</td></tr>
<?php
			}
		}
   ?>

</table>
</form>
</td>
</tr>
</table>


<script type="text/javascript">
$(document).ready(function(){
	
	
  $('li a[href*=#]').click(function() {
  	
  	$('li').removeClass('active');
   $(this).parent().addClass('active');
  	
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
    && location.hostname == this.hostname) {
      var $target = $(this.hash);
      $target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');
      
      if ($target.length) {
        var targetOffset = $target.offset().top;
        $('html,body')
        .stop().animate({scrollTop: targetOffset}, 1300, 'easeOutBounce');
       return false;
      }
    }
  });	
});
</script>

</body>
</html>