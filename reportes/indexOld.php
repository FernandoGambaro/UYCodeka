<?php
ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

require_once __DIR__ .'/../classes/class_session.php';
require_once __DIR__ .'/../common/languages.php';
require_once __DIR__ .'/../common/verificopermisos.php'; 
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/fechas.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


if (!$s = new session()) {
	  echo "<h2>"._('Ocurrió un error al iniciar session!')."</h2>";
	  echo $s->log;
	  exit();
  }

  if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
  {
	  //*user is not logged in*/
	  //echo "<script>window.top.location.href='../index.php'; </script>";  
  } else {
	 $loggedAt=$s->data['loggedAt'];
	 $timeOut=$s->data['timeOut'];
	 if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
		 $s->data['act']="timeout";
		  $s->save();  	
			//header("Location:../index.php");	
		  //echo "<script>window.top.location.href='../index.php'; </script>";
		 exit;
	 }
	 $s->data['loggedAt']= time();
	 $s->save();
  }
  
  $UserID=$s->data['UserID'];
  $UserNom=$s->data['UserNom'];
  $UserApe=$s->data['UserApe'];
  $UserTpo=$s->data['UserTpo'];
  $paleta=isset($s->data['paleta']) ? $s->data['paleta'] : 1;

  $objMon = new Consultas('monedas');
  $objMon->Select();
  $objMon->Where('orden', '3', '<');
  $objMon->Where('borrado', '0');
  $objMon->Orden('orden', 'ASC');
  $selMon=$objMon->Ejecutar();
  $filasMon=$selMon['datos'];

  $xmon=1;
  foreach($filasMon as $fila){
    $moneda='moneda'.$xmon;
         $$moneda = $fila['simbolo'];
      $xmon++;
  }


  $fechainicio = data_first_month_day(date('Y-m-d'));
  $fechafin = data_last_month_day(date('Y-m-d'));

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo $page_title; ?></title>

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<link href="../library/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css" rel="stylesheet"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../library/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="../library/bootstrap/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="../library/js/jquery-ui.min.css" />
    <!-- link rel="stylesheet" href="../pacientes/assets/css/style.css" / -->

    <link href="../library/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="../library/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>


	<link rel="stylesheet" href="../library/colorbox/colorbox.css?u=<?php echo time();?>" />
	<script src="../library/colorbox/jquery.colorbox.js?u=<?php echo time();?>"></script>

    <script src="../library/js/cargadatos.js" type="text/javascript"></script>
    <script src="../library/js/OpenWindow.js?u=<?php echo time();?>" type="text/javascript"></script>

<link href="../library/bootstrap-date/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../library/bootstrap-date/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../library/bootstrap-date/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>

<link rel="stylesheet" href="../library/toastmessage/jquery.toastmessage.css" type="text/css">
<script src="../library/toastmessage/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../library/toastmessage/message.js" type="text/javascript"></script>

<script type="text/javascript" src="../library/js/jquery.keyz.js"></script>

 
<script  src="../library/js/jquery-ui.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../library/estilos/font-awesome.min.css">
<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../library/estilos/customCSS.css" rel="stylesheet">


<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Reportes&nbsp;";

var tst='';
var alto=parent.document.getElementById("alto").value-180;

var totales=alto/22;
	 totales=totales-1;

$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

});
</script>
<script type="text/javascript">

function Comparar_Fecha(Obj1,Obj2)
{
	String1 = Obj1;
	String2 = Obj2;
	// Si los dias y los meses llegan con un valor menor que 10
	// Se concatena un 0 a cada valor dentro del string
	if (String1.substring(1,2)=="/") {
	String1="0"+String1
	}
	if (String1.substring(4,5)=="/"){
	String1=String1.substring(0,3)+"0"+String1.substring(3,9)
	}
	
	if (String2.substring(1,2)=="/") {
	String2="0"+String2
	}
	if (String2.substring(4,5)=="/"){
	String2=String2.substring(0,3)+"0"+String2.substring(3,9)
	}
	
	dia1=String1.substring(0,2);
	mes1=String1.substring(3,5);
	anyo1=String1.substring(6,10);
	dia2=String2.substring(0,2);
	mes2=String2.substring(3,5);
	anyo2=String2.substring(6,10);
	
	
	if (dia1 == "08") // parseInt("08") == 10 base octogonal
	dia1 = "8";
	if (dia1 == '09') // parseInt("09") == 11 base octogonal
	dia1 = "9";
	if (mes1 == "08") // parseInt("08") == 10 base octogonal
	mes1 = "8";
	if (mes1 == "09") // parseInt("09") == 11 base octogonal
	mes1 = "9";
	if (dia2 == "08") // parseInt("08") == 10 base octogonal
	dia2 = "8";
	if (dia2 == '09') // parseInt("09") == 11 base octogonal
	dia2 = "9";
	if (mes2 == "08") // parseInt("08") == 10 base octogonal
	mes2 = "8";
	if (mes2 == "09") // parseInt("09") == 11 base octogonal
	mes2 = "9";
	
	dia1=parseInt(dia1);
	dia2=parseInt(dia2);
	mes1=parseInt(mes1);
	mes2=parseInt(mes2);
	anyo1=parseInt(anyo1);
	anyo2=parseInt(anyo2);
	
	if (anyo1>anyo2)
	{
	return false;
	}
	
	if ((anyo1==anyo2) && (mes1>mes2))
	{
	return false;
	}
	if ((anyo1==anyo2) && (mes1==mes2) && (dia1>dia2))
	{
	return false;
	}

return true;
}

</script>		
		
		<script language="javascript">
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		function inicio() {
			$('#enviomail').hide();
			document.getElementById("form_busqueda").submit();			
		}
		
		function imprimir() {
			//defaultPrevented;

			event.preventDefault();
			var url ='';
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			var codusuarios=document.getElementById("codusuarios").value;
			var localidad=document.getElementById("localidad").value;
			var codcliente=document.getElementById("aCliente").value;
			var cboProvincias=document.getElementById("cboProvincias").value;
			var moneda=document.getElementById("moneda").value;
			
			var tiporeporte=document.getElementById("tiporeporte").value;
			
			if ($('#opcionesaexcel').is(":checked") && tiporeporte!=''){
				if (tiporeporte==4) {
					url = "../excel/CierreAnualizadoExcel.php?codcliente="+codcliente+"&provincia="+cboProvincias+"&localidad="+localidad+"&codusuarios="+codusuarios+"&fechainicio="+fechainicio+"&fechafin="+fechafin;
				} 
				if (tiporeporte==2) {
					url = "../excel/ResumenDeCuentaExcel.php?codcliente="+codcliente+"&moneda="+moneda+"&provincia="+cboProvincias+"&localidad="+localidad+"&codusuarios="+codusuarios+"&fechainicio="+fechainicio+"&fechafin="+fechafin;
				} 				
				if (tiporeporte==3) {
					url = "../excel/LiquidacionDeComisionesExcel.php?codcliente="+codcliente+"&provincia="+cboProvincias+"&localidad="+localidad+"&codusuarios="+codusuarios+"&fechainicio="+fechainicio+"&fechafin="+fechafin;
				} 
				if (tiporeporte==5) {
					url = "EstadoDeCuentaTodosLosClientes.php?codcliente="+codcliente+"&provincia="+cboProvincias+"&localidad="+localidad+"&codusuarios="+codusuarios+"&fechainicio="+fechainicio+"&fechafin="+fechafin;
				} 
//alert(url);
				$.msgBox({ type: "prompt",
					 title: "Ingrese el nombre del archivo sin extención",
					 inputs: [
					 { header: "Nombre de Archivo", type: "text", name: "nombre" }],
					 buttons: [
					 { value: "Aceptar" }, { value:"Cancelar" }],
					 success: function (result, values) {
						$(values).each(function (index, input) {
						v =  input.value ;
						});		
				if (v!="") {
					$.get("../excel/preparo.php?file="+v,function (data,status) { });
			      window.parent.progressExcelBar(1,v);
					$.get(url+"&file="+v, function(data, status) {
						if(status == 'success'){	
						$('#downloadFrame').remove(); // This shouldn't fail if frame doesn't exist
   					$('body').append('<iframe id="downloadFrame" style="display:none"></iframe>');
   					$('#downloadFrame').attr('src','../tmp/'+v+'.xlsx');	
							return false;
				  		}else{					
						showWarningToast('Se produjo un error, intentelo mas tarde');
				  		}
					});    											
				} else {
							if (result!="Cancelar") {
								showWarningToast('Nombre de archivo incorrecto.');
							}
						}
					}
				});	
				//Finaliza exportar a excel
			} else {
				if (tiporeporte==1) {
					window.open("cierremes.php?codcliente="+codcliente+"&provincia="+cboProvincias+"&localidad="+localidad+"&codusuarios="+codusuarios+"&fechainicio="+fechainicio+"&fechafin="+fechafin);
				}
				if (tiporeporte==2) {
					window.open("EstadoDeCuenta.php?moneda="+moneda+"&codcliente="+codcliente+"&provincia="+cboProvincias+"&localidad="+localidad+"&codusuarios="+codusuarios+"&fechainicio="+fechainicio+"&fechafin="+fechafin);
				}
			
			}
		}	
				
	
	function actualizar() {
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;

		if (Comparar_Fecha(fechainicio, fechafin)){
 			document.getElementById("form_busqueda").submit();
 	    } 		
	}	

	function submitformrepo() {
		//event.preventDefault();
		$("#Image11").prop('disabled', true);
		
		$('#grafico').hide();
		$('#graficodetalles').hide();		
		$('#enviomail').hide();
			var codcliente=$('#aCliente').val();

		var tiporeporte=form_busqueda.tiporeporte.options[form_busqueda.tiporeporte.selectedIndex].value;
		if (tiporeporte==1) {
		$('#grafico').show();
		$('#graficodetalles').show();	
		$('#form_busqueda').attr('action', 'cierremes.php');
		}
		if (tiporeporte==2 && codcliente == '') {//Estado de cuenta de un cliente
				showWarningToast('Tipo de reporte requiere seleccionar cliente');
				$("#tiporeporte").val('1').attr("selected", "selected");
				$("#tiporeporte").selectmenu('refresh');
				return false;
				} 
				 if (tiporeporte==2) {
				$('#enviomail').show();
				$('#form_busqueda').attr('action', 'EstadoDeCuenta.php');
		}
		if (tiporeporte==5) {
			var tipomoneda=form_busqueda.moneda.options[form_busqueda.moneda.selectedIndex].value;
				if (tipomoneda==0) {
					showWarningToast('Tipo de reporte requiere seleccionar moneda');
					$("#tiporeporte").val('1').attr("selected", "selected");
//					$("#tiporeporte").selectmenu('refresh');
					return false;
				}
				$("#fechafin").val($("#fechainicio").val());
				$("#Image11").prop('disabled', true);
				$('#form_busqueda').attr('action', 'EstadoDeCuentaTodosLosClientes.php');
		}					
		if (tiporeporte==3) {
				$('#form_busqueda').attr('action', 'comisiones.php');
		}
		if (tiporeporte==4) {
				$('#form_busqueda').attr('action', 'calculodeiva.php');
		}
		document.getElementById("form_busqueda").submit();
		return true;	
	} 

	function jpgrafico(tipo) {
		event.preventDefault();
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			if (tipo=='d') {			
			document.getElementById('frame_rejilla').src = 'graficotorta.php?fechainicio='+fechainicio+'&fechafin='+fechafin;				
			} else {
			document.getElementById('frame_rejilla').src = 'graficobarras.php?fechainicio='+fechainicio+'&fechafin='+fechafin;	
			}
	}
	
function progressExcelBar(xx,file) {
	window.parent.progressExcelBar(xx,file);
}		
	
		</script>

  
<link href="../library/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="../library/jquery-ui/jquery-ui.js"></script>


<script>
$.ui.autocomplete.prototype._renderItem = function(ul, item) {
  var re = new RegExp($.trim(this.term.toLowerCase()));
  var t = item.label.replace(re, "<span >" + $.trim(this.term.toLowerCase()) +
    "</span>");
  return $("<li></li>")
    .data("item.autocomplete", item)
    .append("<a>" + t + "</a>")
    .appendTo(ul);
};

 $(document).ready(function () {
         
    var monArray = new Array();
    monArray[1]="<?php echo $moneda1;?>";
	monArray[2]="<?php echo $moneda2;?>";


    $("#nombre").autocomplete({
        source: '../common/busco_clientes.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {
	
			var name = ui.item.value;
			var thisValue = ui.item.data;
			var pref=thisValue.split("~")[0];
			var nombre=thisValue.split("~")[1];
			var nif=thisValue.split("~")[2];
	
			$("#codcliente").val(pref);
			$("#Acodcliente").val(nombre);
			$("#Bcodcliente").val(pref);
      $("#nrut").val(nif);

			}
	  }).autocomplete("widget").addClass("fixed-height");

});
</script>		
		
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="cierremes.php" target="frame_rejilla">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0><tr>
					<td valign="top" width="98%">
					  <table class="fuente8" cellspacing=0 cellpadding=2 border=0 width="100%"><tr>
							 <td valign="top">Cliente</td>
						    <td colspan="2"><input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" data-index="1">
						    <input name="codcliente" type="hidden" id="aCliente">
					      </td>						  
						<td valign="top">
							Fecha&nbsp;de&nbsp;inicio</td>
						  <td valign="top"><input id="fechainicio" type="text" class="cajaPequena form_date" name="fechainicio" maxlength="10" value="<?php echo implota($fechainicio);?>" readonly>
						  <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">					  
						  </td>
						  <td valign="top">Fecha&nbsp;de&nbsp;fin</td>
						  <td valign="top"><input id="fechafin" type="text" class="cajaPequena form_date" name="fechafin" maxlength="10" value="<?php echo implota($fechafin);?>" readonly>
						  <img src="../img/calendario.png" name="Image11" id="Image11" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
					  </td>
						<td rowspan="3" valign="top" colspan="2">
						<fieldset><legend>&nbsp;Tipo de reporte&nbsp;</legend>
						<select name="tiporeporte" id="tiporeporte" class="comboGrande"  onchange="submitformrepo();">
							<option value="1">Detalles compra/venta</option>
							<option value="2">Estado de cuenta cliente</option>
							<option value="5">Estado de cuenta todos los cliente</option>
							<option value="3">Liquidación de comisiones</option>
							<option value="4">Cierre anualizado</option>							
						</select>					
						<br>&nbsp;<br>
						<label>En lugar de imprimir, exportar a Excel?
  							<input id="opcionesaexcel" name="opcionesaexcel" type="checkbox" checked="true" value="0">
  							<span></span>
						</label>						
						</fieldset>
						</td>
					  </tr>
					  <tr>										  
					  <td valign="top">Moneda</td><td valign="top">
						 <select name="amoneda" id="moneda" class="cajaMedia" onchange="document.getElementById('form_busqueda').submit();">
						 <option value="0" selected="selected">Todas las monedas</option>
						 <?php
                            $nombre='';
                                $objMon = new Consultas('monedas');
                                $objMon->Select();
                                $objMon->Where('orden', '3', '<');
                                $objMon->Where('borrado', '0');
                                $objMon->Orden('orden', 'ASC');
                                $selMon=$objMon->Ejecutar();
                                $filasMon=$selMon['datos'];
                                $xmon=1;
                                foreach($filasMon as $fila){
                                    ?> <option value="<?php echo $xmon;?>"><?php echo $fila['simbolo'];?></option> <?php
                                    $xmon++;
                                }	
                            ?>
						</select></td>	
						<td valign="top">Vendedor</td>
						
							<td colspan="2" valign="top"><select id="codusuarios" name="codusuarios" class="comboGrande">
							<option value="0" selected="selected">Seleccione un ejecutivo de cuenta</option>

                            <?php
                                $objMon = new Consultas('usuarios');
                                $objMon->Select();
                                $objMon->Where('tratamiento', '3', '=');
                                $objMon->Where('estado', '0');
                                $selMon=$objMon->Ejecutar();
                                $filasMon=$selMon['datos'];
                                $xmon=1;
                                foreach($filasMon as $fila){
                                    ?> <option value="<?php echo $fila['codusuarios'];?>"><?php echo $fila['nombre']. ' '. $fila['apellido'];?></option> <?php
                                }	
                            ?>				
							</select>

							</td>											  
							<td valign="top" rowspan="2" colspan="2">

						</td>		
						</tr>						  
						<tr>
					  
						<td valign="top">Departamentos</td>
							<td valign="top"><select id="cboProvincias" name="cboProvincias" class="comboMedio" onchange="document.getElementById('form_busqueda').submit();">
								<option value="0" selected>Todos los departamentos</option>
								<?php
								$objMon = new Consultas('provincias');
                                $objMon->Select();
								$objMon->Orden('nombreprovincia', 'ASC');
                                $selMon=$objMon->Ejecutar();
                                $filasMon=$selMon['datos'];
                                $xmon=1;
                                foreach($filasMon as $fila){
                                    ?> <option value="<?php echo $fila['codprovincia'];?>"><?php echo $fila['nombreprovincia'];?></option> <?php
                                }
								?>				
							</select>
						</td>
						<td valign="top">Localidad</td>
						<td valign="top" colspan="2"><input id="localidad" type="text" class="cajaGrande" name="localidad" maxlength="30" onkeyup="document.getElementById('form_busqueda').submit();"></td>
					</tr></table>
			  </div>
			  <table class="fuente8" width="90%" cellspacing=0 cellpadding=2 border=0>
			  	<tr>

				<td width="100%" align="center">
			 	<div>
			 	<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Limpiar</button>			 	
				<button class="boletin" onClick="buscar();" onMouseOver="style.cursor=cursor"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</button>
				<button class="boletin" onClick="javascript:jpgrafico('d');" onMouseOver="style.cursor=cursor" id="graficodetalles"><i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp;Gráfico con detalles</button>				
				<button class="boletin" onClick="javascript:jpgrafico('s');" onMouseOver="style.cursor=cursor" id="grafico"><i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp;Gráfico simple</button>				
				<button class="boletin" onClick="javascript:envio();" onMouseOver="style.cursor=cursor" id="enviomail"><i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;Envio mail cliente</button>				
					<?php
						if ($reportes=="true") {?>
						<button class="boletin" onClick="imprimir();" onMouseOver="style.cursor=cursor">
					<i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
					<?php } ?>
				</div>
				</td>
			  </table>

				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
			</form>
					<p align="center">
					<iframe width="90%" height="410" id="frame_rejilla" name="frame_rejilla" frameborder="0">
					</iframe></p>
					<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					
					</iframe>
			</div>
		  </div>			
		</div>
<script type="text/javascript">
 	$('.form_date').datetimepicker({
        minView: 2, pickTime: false,
        format: 'dd/mm/yyyy',
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
        forceParse: 0,
    }).on('changeDate', function (ev) {
       
    });


</script>
<script>


document.form_busqueda.submit();


</script>

	</body>
</html>