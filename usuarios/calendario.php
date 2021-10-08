<?php 
require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

include ("../funciones/fechas.php");

require_once __DIR__ .'/../classes/class_session.php';

if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
  }
  
  $UserID=$s->data['UserID'];


$codusuarios=$_GET['codusuarios'];

$obj = new Consultas('usuarios');
$obj->Select();
$obj->Where('codusuarios', $codusuarios, '=' );
$obj->Where('borrado', '0' );
//var_dump($obj);
$paciente = $obj->Ejecutar();
$row=$paciente['datos'][0];

//$sel_resultado="SELECT * FROM usuarios WHERE borrado=0 AND codusuarios='".$codusuarios."' limit 1";

		//$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
    //$contador=0;
		$usuario=$row["nombre"]." ".$row["apellido"];
		$pin=$row["pin"];
    $nempleado=$row['nempleado'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
		<link href="../library/estilos/estilos.css" type="text/css" rel="stylesheet">
    <!-- script src="../library/calendario/jscal2.js"></script>
    <script src="../library/calendario/lang/es.js"></script -->
    <link rel="stylesheet" type="text/css" href="../library/calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../library/calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../library/calendario/css/win2k/win2k.css" />			

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">

<!-- link rel="stylesheet" href="../css3/w3colors.css" -->

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

  <link rel="stylesheet" href="../library/js/msgBoxLight.css?u=<?php echo time();?>" type="text/css">
<script type="text/javascript" src="../library/js/jquery.msgBox.js"></script>

<link href="../library/bootstrap-date/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../library/bootstrap-date/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../library/bootstrap-date/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>

<link rel="stylesheet" href="../library/toastmessage/jquery.toastmessage.css?u=<?php echo time();?>" type="text/css">
<script src="../library/toastmessage/jquery.toastmessage.js?u=<?php echo time();?>" type="text/javascript"></script>
<script src="../library/toastmessage/message.js?u=<?php echo time();?>" type="text/javascript"></script>

<script src="validar.js" type="text/javascript"></script>


<script type="text/javascript" src="../library/js/jquery.keyz.js"></script>

 
<script  src="../library/js/jquery-ui.js"></script>


<!-- iconos para los botones -->       
<link rel="stylesheet" href="../library/estilos/font-awesome.min.css">
<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../library/estilos/customCSS.css" rel="stylesheet">

<script src='../library/fullcalendar/lib/main.js'></script>
<link href="../library/fullcalendar/lib/main.css" rel="stylesheet">
<script src='../library/fullcalendar/lib/locales/es.js'></script>


<script>
  function padNmb(nStr, nLen) {
      var sRes = String(nStr);
      var sCeros = "0000000000";
      return sCeros.substr(0, nLen - sRes.length) + sRes;
  }

  function stringToSeconds(tiempo) {
      var sep1 = tiempo.indexOf(":");
      var sep2 = tiempo.lastIndexOf(":");
      var hor = tiempo.substr(0, sep1);
      var min = tiempo.substr(sep1 + 1, sep2 - sep1 - 1);
      var sec = tiempo.substr(sep2 + 1);
      return (Number(sec) + (Number(min) * 60) + (Number(hor) * 3600));
  }
  function secondsToTime(secs) {
      var hor = Math.floor(secs / 3600);
      var min = Math.floor((secs - (hor * 3600)) / 60);
      var sec = secs - (hor * 3600) - (min * 60);
      return padNmb(hor, 2) + ":" + padNmb(min, 2) + ":" + padNmb(sec, 2);
  }

  function sumarHoras(t1, t2) {
      var secs1 = stringToSeconds(t1);
      var secs2 = stringToSeconds(t2);
      var secsDif = secs1 + secs2;
      return secondsToTime(secsDif);
  }
              
  function restarHoras(t1,t2) {
    var secs1 = stringToSeconds(t1);
    var secs2 = stringToSeconds(t2);
    var secsDif = secs2 - secs1;
    return secondsToTime(secsDif);
  }


var alto=window.top.document.getElementById("alto").value-160;

jQuery.throughObject = function(obj){
    for(var attr in obj){
      //console.log(attr + ' : ' + obj[attr]);
      if(typeof obj[attr] === 'object'){
        jQuery.throughObject(obj[attr]);
      }
    }
  }

</script>

<script>
var totaltime=0;
var totalhrs=[];

var currentMousePos = {
    x: -1,
    y: -1
};

jQuery(document).on("mousemove", function (event) {
   currentMousePos.x = event.pageX;
   currentMousePos.y = event.pageY;
});

function isElemOverDiv() {
   var trashEl = jQuery('#trash');
   var ofs = trashEl.offset();
   var x1 = ofs.left;
   var x2 = ofs.left + trashEl.outerWidth(true);
   var y1 = ofs.top;
   var y2 = ofs.top + trashEl.outerHeight(true);
   if (currentMousePos.x >= x1 && currentMousePos.x <= x2 &&      currentMousePos.y >= y1 && currentMousePos.y <= y2) {      return true;    }    return false; 
}

moment.locale('es', {
  months: 'Enero_Febrero_Marzo_Abril_Mayo_Junio_Julio_Agosto_Septiembre_Octubre_Noviembre_Diciembre'.split('_'),
  monthsShort: 'Enero._Feb._Mar_Abr._May_Jun_Jul._Ago_Sept._Oct._Nov._Dec.'.split('_'),
  weekdays: 'Domingo_Lunes_Martes_Miercoles_Jueves_Viernes_Sabado'.split('_'),
  weekdaysShort: 'Dom._Lun._Mar._Mier._Jue._Vier._Sab.'.split('_'),
  weekdaysMin: 'Do_Lu_Ma_Mi_Ju_Vi_Sa'.split('_')
}
);


var calendar;
var instanceId;

document.addEventListener('DOMContentLoaded', function() {
  var codusuarios= $('#codusuarios').val();
  var nempleado= $('#nempleado').val();
  var calendarEl = document.getElementById('calendar');
  
  console.log(nempleado);
  function handleDatesRender(arg) {
    console.log('viewType:', arg.view.calendar.state.viewType);
  }

  var date = new Date();

  calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'timeGridWeek',
    locale: 'es',
    initialDate: date,
    height: 450, 
    allDaySlot: false,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay',
      
    },
    events: {
          url: 'php/get-events3.php?nempleado='+nempleado+'&codusuarios='+codusuarios,
          type: 'POST',
          data: { nempleado: nempleado, codusuarios:codusuarios  },        
          error: function() {
          $('#script-warning').show();
          },
          success: function(doc) {
              $.each(doc, function (index, value) {
              // console.log(value);
            });
            //console.log(doc[0].start);
            var tmp = doc[0].start.split(" ");
          $('#ExportDate').val(tmp[0]);
          var keyaux='';
          var diffaux='00:00:00';
          $.each(doc, function(key, element) {
            if (element.suma!='n'){
              start_actual_time = new Date(element.start);
              end_actual_time = new Date(element.end);
      
              var key='dailytotal-'+moment(start_actual_time, "YYYY-MM-DD").format('YYYY-MM-DD');
              var diff = end_actual_time - start_actual_time;
              var diffSeconds = diff / 1000;
              var HH = Math.floor(diffSeconds / 3600).toFixed(0);
              var MM = (Math.floor(diffSeconds % 3600) / 60).toFixed(0);
              var SS = (Math.floor(diffSeconds % 3600) / 60/60).toFixed(0);
            
            if (key !== keyaux) {
              diffaux='00:00:00';
            } 
              inicio=HH + ":"+ MM + ":"+SS;
              diffaux= sumarHoras(inicio,diffaux);
              keyaux=key;
              totalhrs[key]=diffaux;
              }
          });
          },
    },
      loading: function(bool) {
        $('#loading').toggle(bool);
      },      
      datesSet: function(info) {
        
        var control = info.view.type;
        //console.log(moment(info.startStr).format('DD/MM/YYYY HH:mm:ss'));

        if(control=='timeGridDay'){
          window.setTimeout(function(){
            var codusuarios= $('#codusuarios').val();
            var cumplir='';
            $.ajax({
              url: 'php/consulto.php',
              data: {"fechafin": moment(info.startStr).format('YYYY-MM-DD HH:mm:ss'), "codusuarios": codusuarios},
              type: 'POST',
              dataType: 'json',
              success: function(response){
                //console.log(response[0].consulta);
                if(response[0].cumplir!='' && control=='timeGridDay'){
                  var texto=response[0].cumplir;
                  $("#calendar").find('.fc-toolbar > div > h2').empty().append(
                    "<div>"+moment(info.startStr).format('dddd D MMMM YYYY') +"<br><span style='font-size: 10px;'> Horario a cumplir: " + texto + "</span></div>"
                  );
                }else{
                  $("#calendar").find('.fc-toolbar > div > h2').empty().append(
                    moment(info.start).format('dddd D MMMM YYYY ')
                  );
                }
              }
            });
            
          },0);
        }else{
          $("#calendar").find('.fc-toolbar > div > h2').empty().append(
            moment(info.start).format('D MMMM YYYY ') + ' al '+ parseInt(moment(info.end).format('D')-1) + moment(info.end).format(' MMMM YYYY')
          );
        }
},
     eventClick: function(info) {
      info.jsEvent.preventDefault(); // don't let the browser navigate
      //console.log(info.event._instance.instanceId);
      instanceId = info.event._instance.instanceId;
      
        endtime = moment(info.event.endStr).locale('es').format('H:mm');
        starttime = moment(info.event.startStr).locale('es').format('dddd, Do MMMM YYYY, h:mm');
        var mywhen = starttime + ' - ' + endtime;
        start = moment(info.event.startStr).format('DD/MM/YYYY H:mm:ss');
        end =  moment(info.event.endStr).format('DD/MM/YYYY H:mm:ss');
        horaini=moment(info.event.startStr).format('H:mm');
        fechaini=moment(info.event.startStr).format('DD/MM/YYYY');
        horafin=moment(info.event.endStr).format('H:mm');
        fechafin = moment(info.event.endStr).format('DD/MM/YYYY');
        var codusuarios= $('#codusuarios').val();
        if(fechafin=='Invalid date'){
          fechafin=fechaini;
        }
        if(horafin=='Invalid date'){
          horafin=horaini;
        }
        //console.log(" fecha fin "+fechafin+' Hora fin'+ horafin);
        $('#horaini').val(horaini);
        $('#fechaini').val(fechaini);
        $('#horafin').val(horafin);
        $('#fechafin').val(fechafin);
        $('comentario').val(info.event.comentario);
        $('#modalTitle').html(info.event.title);
        $('#modalWhen').text(mywhen);
        $('#eventID').val(info.event.id);
        $('#className').val(info.event.className); 

        if(info.event.id!='null'){
          $('#action').val('upd');
          $('#modalaacion').val('upd');
        } else {
          $('#action').val('add');
          $('#modalaacion').val('add');
        }
        $('#calendarModal').modal('show');

        //console.log("eventID "+ info.event.id);

        if (info.event.classNames!='custom') {
          $('#validarButton').show();
          $('#NovalidarButton').hide();
        }else {
          $('#validarButton').hide();
          $('#NovalidarButton').show();
        }
			 if(fechaini !=fechafin ){
			 	$('#fechafin').css('background-color', '#bed7f3');
			 }  else {
			 	$('#fechafin').css('background-color', '#ffffff');
			 }    
       $.ajax({
          url: 'php/consulto.php',
          data: {"fechafin": fechaini, "codusuarios": codusuarios},
          type: 'POST',
          dataType: 'json',
          success: function(response){
            //console.log(response[0]);
          var horario=response[0].horario;
          $('#horario').html(horario);       
          }
        });
          $("#descripcion").prop('selectedIndex',event.title);
 
      }
    });
  calendar.render();

  $('#submitButton').on('click', function(e){ // add event submit
      // We don't want this to act as a link so cancel the link action
      e.preventDefault();
      doSubmit(); // send to form submit function
  });
 
  $('#deleteButton').on('click', function(e){ 
           var eventID = $('#eventID').val();
            $("#calendarModal").modal('hide');
           e.preventDefault();
 				$.msgBox({
				    title: "Alerta",
				    content: "Quiere eliminar datos?",
				    type: "confirm",
				    buttons: [{ value: "Si" }, { value: "Cancelar"}],
				    success: function (result) {
				        if (result == "Si") {
								$.msgBox({ type: "prompt",
								    title: "Autorización",
								    inputs: [
								    { header: "Contraseña", type: "password", name: "password" }],
								    buttons: [
								    { value: "Aceptar" }, { value:"Cancelar" }],
								    success: function (result, values) {
											$(values).each(function (index, input) {
                     					v =  input.value ;
                 						});									    	
    										if (v=="1234") {
												$.ajax({
								          	url: 'php/save-event.php',
								          	type: 'POST',
                            cache: false,
                            data: {"action": "borrar", "eventID": ""+eventID+""},
                            dataType: "json",
								          	success: function(response){
								          		console.log(response);
									            if(response[0].estado == 'ok')
                              calendar.refetchEvents();
								   	           //$('#calendar').fullCalendar('removeEvents', eventID);
								      	        //$('#calendar').fullCalendar('addEventSource', JSON.parse(json_events));
								         	 },
								          	error: function(e){
								          	showWarningToast('Error: ');
								          	}
								       	});
											} else {
												showWarningToast('Contraseña erronea');
											}
										}
								});					        	
				        }
				    }
				});
       });


    function doSubmit(){ // add event
        
        var title = $('#title').val();
        var startTime = $('#fechaini').val()+' '+$('#horaini').val()+':00';
        var endTime = $('#fechafin').val()+' '+$('#horafin').val()+':00';
        var action = $('#action').val();
        var eventID = $('#eventID').val();
        var className= $('#className').val();
        var codusuarios= $('#codusuarios').val();
        var pin = $('#pin').val();
        //console.log($('#comentario'));
        var d1 = new Date(startTime);
        var d2 = new Date(endTime);

        console.log(d1.getTime() === d2.getTime());

        if(!(d1.getTime() === d2.getTime())){

          if($('#comentario')!=''){
          var comentario= $('#comentario');
          }else{
          var comentario='';
          }
          var descripcion = $('#descripcion').find('option:selected').val();
          console.log('comentario='+comentario+'&pin='+pin+'&action='+action+'&eventID='+eventID+'&title='+title+'&start='+startTime+'&end='+endTime+'&descripcion='+descripcion+'&codusuarios='+codusuarios);
          //return false;
          $("#calendarModal").modal('hide');

          $.ajax({
          type: "POST",
          url: "php/save-event.php",
          cache: false,
          data: {"action": ""+action+"", "start": ""+startTime+"", "end": ""+endTime+"",
             "title": ""+title+"", "descripcion": ""+descripcion+"", "codusuarios": ""+codusuarios+"",
              "eventID": ""+eventID+"", "pin": ""+pin+"", "comentario": ""+comentario+""},
          dataType: "json",
          success: function(json){  
              if(json[0].id!='undefined' && json[0].id!='null' && json[0].id!='' ){
            console.log(json[0]);
            console.log(action);
              startTime = moment(startTime, "YYYY-MM-DD H:mm:ss").format();// this should be date object
              endTime = moment(endTime, "YYYY-MM-DD H:mm:ss").format(); // this should be date object
                  var events=new Array();
                  event = new Object();    
                  event.id=json[0].id;
                  event.title = title; // this should be string
                  event.start = startTime;// this should be date object
                  event.end = endTime; // this should be date object
                  event.className = "custom";
                  events.push(event);

                if (action!='add') {

                  //event.remove();
                  calendar.addEventSource(events);
                  calendar.refetchEvents();
                
                } else if (action=='add') {
                  calendar.addEventSource(events);
                  calendar.refetchEvents();	              		
                }
              }else{
                showToast('<?php echo _('Error, Número de id no válido');?>', 'info');
              }
                
              },
          error: function(e){
            //console.log(e.responseText);
            showToast('<?php echo _('Error, intente mas tarde:');?>', 'info');
          },
          cache: false,
          });

        }else{
          showToast('<?php echo _('Hora final no válida:');?>', 'error');
        }
    }

      $('#validarButton').on('click', function(e){ // Válido la hora seleccionada
           e.preventDefault();
           $("#calendarModal").modal('hide');
           $('#action').val('validar');
				doSubmit();
		  });

      $('#NovalidarButton').on('click', function(e){ // Válido la hora seleccionada
           e.preventDefault();
           $("#calendarModal").modal('hide');
           $('#action').val('novalidar');
				doSubmit();
		  });
         
       $('#salirButton').on('click', function(e){ // add event submit
           // We don't want this to act as a link so cancel the link action
           e.preventDefault();
           $("#calendarModal").modal('hide');
				    $('#modalTitle').html(''); // this should be string
				   $('#fechaini').val('');
				   $('#fechafin').val('');
				   $('#horaini').val('');// this should be date object
				   $('#horafin').val('');// this should be date object
				   // $('#endTime').val(''); // this should be date object
           $('#horario').val(''); // this should be date object
				    $('#eventID').val(''); // this should be date object

       });   
       
       $('#ExportButton').on('click', function(e){ 
           e.preventDefault();
				exportar(0) ;
       });   
       $('#ExportButtonCompleto').on('click', function(e){ 
           e.preventDefault();
				exportar(1) ;
       });   

function exportar(tipo) {
  var codusuarios= $('#codusuarios').val();
  var ExportDate=$('#ExportDate').val();
  //console.log(ExportDate);
  if (tipo==0) {
    url = "../excel/AtendanceCalendar.php?fechainicio="+ExportDate+"&codusuarios="+codusuarios;
  } else {
    url = "../excel/AtendanceHorasCompleto.php?fechainicio="+ExportDate+"&codusuarios="+codusuarios;						
  }
console.log(url);
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
        top.parent.showModal(1);
          $.get(url+"&file="+v, function(data, status) {
          if(status == 'success'){	

            var link = document.createElement('a');
link.target = '_blank';
link.href = '../tmp/'+v+'.xlsx';
document.body.appendChild(link); // Required for Firefox
link.click();
link.remove();


         // $('#downloadFrame').remove(); // This shouldn't fail if frame doesn't exist
         // $('body').append('<iframe id="downloadFrame" style="display:none"></iframe>');
          //$('#downloadFrame').attr('src','../tmp/'+v+'.xlsx');
          top.parent.showModal(0);	
            return false;
            }else{	
              top.parent.showModal(0);				
              showToast('<?php echo _('Error, intente mas tarde:');?>', 'info');
            }
        });    											
      } else {
        if (result!="Cancelar") {
          top.parent.showModal(0);
          showToast('<?php echo _('Error, intente mas tarde:');?>', 'info');
        }
      }					  														    	
    }
  });
}
  });

</script>
<style>

  body {
    margin: 0;
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;
  }

  #script-warning {
    display: none;
    background: #eee;
    border-bottom: 1px solid #ddd;
    padding: 0 10px;
    line-height: 40px;
    text-align: center;
    font-weight: bold;
    font-size: 12px;
    color: red;
  }

  #loading {
    display: none;
    position: absolute;
    top: 10px;
    right: 10px;
  }

  #calendar {
    max-width: 900px;
    margin: 20px auto;
    padding: 0 10px;
 
  }
#deleteButton{
display: block;
}
#submitButton{
display: block;
}

.custom,
.custom div,
.custom span {
	
    background-color: #4CAF50!important; /* background color */
    border-color: green;     /* border color */
    color: yellow;           /* text color */
}
.custom:hover{
	color: black;
}
.customuser,
.customuser div,
.customuser span {
    background-color: #757575!important; /* background color */
    border-color: green;     /* border color */
    color: white;           /* text color */
}
.azul,
.azul div,
.azul span {
    background-color: #2196F3!important; /* background color */
    border-color: green;     /* border color */
    color: white;           /* text color */
}
.azul:hover{
	color: black;
}
.tarde,
.tarde div,
.tarde span {
    background-color: #f44336!important; /* background color */
    border-color: #000 ;     /* border color */
    color: black;           /* text color */
}
.tarde:hover{
    color: black;           /* text color */
}
.fueradehora,
.fueradehora div,
.fueradehora span {
    background-color: #757575!important; /* background color */
    border-color: #757575!important;     /* border color */
    color: white;           /* text color */
}
.fueradehora:hover{
    color: black;           /* text color */
}
</style>
		<script language="javascript">
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}

		</script>
<style type="text/css">
.time-input-container{position:relative;display:inline-block;}
.time-input-field{width:90px;height:18px;padding:4px 4px 4px 24px!important;border-radius:2px;border:1px solid #aaa;-webkit-box-sizing:border-box;box-sizing:border-box;height:27px;}
.time-input-icon{width:16px;height:16px;display:block;position:absolute;top:50%;left:5px;margin-top:-6px;cursor:pointer;color:#999;}

.duration-input.hours-and-minutes>label{display:inline-block;}
.duration-input.hours-and-minutes>label>input{width:30px;height:27px;border:1px solid #aaa;padding:4px;border-radius:2px;}
.duration-input.hours-and-minutes>label>span{display:block;color:#aaa;margin-left:0;}

</style>		
</head>
<body>
		<div id="pagina">
				<div align="center">
				<div id="tituloForm" class="header">Horas realizadas usuario: <?php echo $usuario;?></div>
				<div >
        
    <!-- Main content -->
        <section class="content">
          <div class="row"><br>
            <div class="col-xs-3">
              
              <!-- /. box -->
              <div class="box box-solid" style="text-align:left; left:-20px">
                <div class="box-header with-border">
                  <h4 class="box-title">Detalles</h4>
                </div>
                <div class="box-body">
                    <div class="row">
                      <div class="col-xs-12">
                        <i class="fa fa-square w3-text-green"></i>&nbsp;Cumple horario
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12">
                        <i class="fa fa-square w3-text-red"></i>&nbsp;No cumple horario
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12">
                        <i class="fa fa-square w3-text-blue"></i>&nbsp;No marca Entrada / Salida
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12">
                        <i class="fa fa-square w3-text-grey"></i>&nbsp;Fuera de horario
                      </div>
                    </div>
                </div>
              </div>
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h4 class="box-title">Exportar a Hoja de cálculo</h4>
                </div>
                <div class="box-body">
                  <div class="row">
                    
                    <input id="ExportDate" type="hidden" value="" />
                      <button type="submit" class="btn boletin" id="ExportButton" data-dismiss="modal">
                      <i class="far fa-file-excel w3-text-blue"></i>&nbsp;Exportar tipo calendario</button>
                  </div> 
                  <div class="row">

                    <button type="submit" class="btn boletin" id="ExportButtonCompleto" data-dismiss="modal">
                    <i class="far fa-file-excel w3-text-blue"></i>&nbsp;Exportar cálculo horas completo</button>

                  </div>
                </div>
              </div>              
            </div>
            <!-- /.col -->



            <div class="col-md-9">

            <fieldset  style="height: 500px;">
            
 						
            <div style="height: 500px; width:100%; overflow:disable; top:0px;">            

              <div class="box box-primary">
                <div class="box-body no-padding">
                  <div id='script-warning'>
                    <code>Imposible acceder a los datos.</code>
                  </div>
                  <div id='loading'>loading...</div>                
                  <!-- THE CALENDAR -->
                  <div id="calendar"></div>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /. box -->
            </div>
            </fieldset>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </section>
        <!-- /.content -->



<div class="container" style="float: left; top:70px; ">
<!-- Modal to Event Details -->
<div id="calendarModal" class="modal fade" >
<div class="modal-dialog">
 <div class="modal-content" style="top:70px; ">
 <div class="modal-header"><div id="modalTitle"></div><div id="modalaacion"></div>
 <button type="button" class="close" data-dismiss="modal">×</button>
 
 </div>
 <div id="modalBody" class="modal-body">
 <div id="modalWhen" style="margin-top:2px;"></div>
  <div class="modal-body">   
		<form id="formulario" name="formulario" method="post" >
    <input type="hidden" name="codusuarios" id="codusuarios" value="<?php echo $codusuarios;?>" >
      <input type="hidden" name="nempleado" id="nempleado" value="<?php echo $nempleado;?>" >
        <div class="row">
        <label class="col-xs-2">Fecha&nbsp;ingreso</label>
          <div class="col-xs-4">
            <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
            <input placeholder="Fecha inicio" class="form-control input-sm" size="26" type="text" name="start" id="fechaini" value="" required autocomplete="false">
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div> 
          </div>

        <label class="col-xs-2">Hora&nbsp;ingreso&nbsp;</label>	
        <div class="col-xs-4">
          <span style="display:inline-block;" class="time-input-container">
            <input class="time-input-field time-input is-timeEntry Sel_hora time form-control input-sm"  type="text" name="end" id="horaini"  placeholder="00:00">
            <span class="time-input-icon fa fa-clock-o fa-lg tipped-delegate show-on-click" ></span>
          </span>                        
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6">
          <select name="descripcion" id="descripcion"  class="form-control input-sm" >
            <option value="0">Horario completo</option>
            <option value="1">Medio horario</option>
            <option value="2">Cambio de horario</option>
            <option value="3">Hora extra</option>
            <option value="4">Horario acordado</option>
            <option value="5">Suplencia</option>
            <option value="6">Coordinación</option>
            <option value="7">Licencia médica</option>
            <option value="8">Otras</option>
          </select>
        </div>
      </div>
      <div class="row">
        <label class="col-xs-2">Fecha salida</label>
        <div class="col-xs-4">                        
          <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input placeholder="Fecha inicio" class="form-control input-sm" size="26" type="text" name="fechafin" id="fechafin" value=""  required autocomplete="false">
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
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
                widgetPositioning: {
                  horizontal: 'auto',
                  vertical: 'auto'
                },
              });
            </script>

        </div>						
        <label class="col-xs-2">hora salida</label>
          <div class="col-xs-4">						
            <span style="display:inline-block; " class="time-input-container">
              <input class="time-input-field time-input is-timeEntry  Sel_hora time form-control input-sm"  type="text" name="horafin" id="horafin" placeholder="00:00" >
              <span class="time-input-icon fa fa-clock-o fa-lg tipped-delegate show-on-click"></span>
            </span>  						                       
          </div>
      </div>

      <div class="row">
        <label class="col-xs-5">Horario real a cumplir</label>
        <div class="col-xs-7" id="horario">
        
        </div>
      </div>

      <div class="row">
        <div class="col-xs-2">Comentario</div>
        <div class="col-xs-10">
          <textarea name="comentario" id="comentario" rows="3" cols="35" class="form-control input-sm"></textarea>								
        </div>
      </div>
     <div class="row"> 
       <div class="col-xs-12">  
      <input type="hidden" id="eventID" name="eventID" />
      <input type="hidden" id="action" name="action" />
      <input type="hidden" id="className" name="className" />
      <input id="title" value="" name="title" type="hidden" />
      <input type="hidden" name="pin" id="pin" value="<?php echo $pin;?>" >
        <div style="clear:both;"></div>
          <div class="modal-footer" style="width:100%; text-align:center;">
          <button type="submit" class="btn btn-danger" id="deleteButton" style="float:left; width:100px;">Eliminar</button>
          <button class="btn boletin" id="submitButton" style="float:right; width:100px;" ><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Guardar</button>
          <button type="submit" class="btn boletin" id="validarButton" data-dismiss="modal" style="display: inline-block; margin:0 auto; width:100px;">Válidar</button>
          <button type="submit" class="btn boletin" id="NovalidarButton" data-dismiss="modal" style="display: inline-block; margin:0 auto; width:100px;">No Válidar</button>

          <button class="btn boletin " id="salirButton" data-dismiss="modal" style="float:right; width:100px;"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
            </div>
       </div>
     </div>
    </form>
			  </div> 

 </div>

 </div>
</div>
</div>
<!--Modal-->
</div>
</div></div></div></div>
	<!-- Bootstrap stylesheet -->

 

<!-- ClockPicker Stylesheet -->
<link rel="stylesheet" type="text/css" href="../library/bootstrap-clockpicker/bootstrap-clockpicker.min.css">

<!-- ClockPicker script -->
<script type="text/javascript" src="../library/bootstrap-clockpicker/bootstrap-clockpicker.js"></script>


<script type="text/javascript">
$('.Sel_hora').click(function(e){
    var input = $(this).clockpicker({
    placement: 'bottom',
    align: 'left',
    autoclose: true,
    'default': 'now',
    });
    e.stopPropagation();
    input.clockpicker('show')
            .clockpicker();
});
</script>
  <!-- script type="text/javascript" src="../js3/jquery.mask.js"></script -->
</body>
</html>