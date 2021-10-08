<?php


ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo $page_title; ?></title>
        <script src="../library/autocomplete/jquery-1.8.3.js"></script>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../library/bootstrap/bootstrap.min.css" />
        <link rel="stylesheet" href="../library/bootstrap/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="../library/js/jquery-ui.min.css" />
        <!-- link rel="stylesheet" href="../pacientes/assets/css/style.css" / -->


<link href="../library/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css" rel="stylesheet"/>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>


	<link rel="stylesheet" href="../library/colorbox/colorbox.css" />
	<script src="../library/colorbox/jquery.colorbox.js"></script>

    <script src="../library/js/OpenWindow.js" type="text/javascript"></script>

<link href="../library/bootstrap-date/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../library/bootstrap-date/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../library/bootstrap-date/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>


<link rel="stylesheet" href="../library/toastmessage/jquery.toastmessage.css" type="text/css">
<script src="../library/toastmessage/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../library/toastmessage/message.js" type="text/javascript"></script>

<script language="javascript">

var alto=window.parent.$("#alto").val()-160;

function setIframeHeight(id) {
    var ifrm = document.getElementById(id);
    var doc = ifrm.contentDocument? ifrm.contentDocument: 
        ifrm.contentWindow.document;
    ifrm.style.visibility = 'hidden';
    ifrm.style.height = "10px"; // reset to minimal height ...
    // IE opt. for bing/msn needs a bit added or scrollbar appears
    ifrm.style.height = alto + "px";
    ifrm.style.visibility = 'visible';
}
</script>		
        
<script type="text/javascript" >

function timecalc() {
var value = parseInt($("[name='DATA[horainiagen]']").val())+1;
$("select#horafinagen").val(value); 
	if (parseInt($("[name='DATA[horainiagen]']").val())=='-1') {
			$("select#horafinagen").val('-1'); 
	}
}

function verifytimecalc(){
	if (parseInt($("[name='DATA[horainiagen]']").val())=='-1') {
			$("select#horafinagen").val('-1'); 
			alert('Debe asignar hora de inicio');
	}	else {
		var valueini = parseInt($("[name='DATA[horainiagen]']").val());
		var valuefin = parseInt($("[name='DATA[horafinagen]']").val());
			if (valuefin<valueini) {
				alert('Hora no válida');
				var value = parseInt($("[name='DATA[horainiagen]']").val())+1;
				$("select#horafinagen").val(value); 
			}
	}
}
</script>        

        <!-- some custom CSS -->
<style>
    body
    {
        font-size: 100%;
    }            
    .left-margin{
        margin:0 .5em 0 0;
    }

    .right-button-margin{
        margin: 0 0 1em 0;
        overflow: hidden;
    }
    .table-fuente10{
        font-size: 10px;
    } 
    @media (min-width: 768px) {
        .container{
                width: 100%;
            }         
    label.col-xs-12 {
    text-align: left !important;
    }
    }  
    fieldset 
    {
        border: 1px solid #ddd;
        margin: 0;
        xmin-width: 0;
        padding: 10px;       
        position: relative;
        border-radius:4px;
        background-color:#f5f5f5;
        padding-left:10px!important;
    }		
    legend
    {
        font-size:14px;
        font-weight:bold;
        margin-bottom: 0px; 
        width: 35%; 
        border: 1px solid #ddd;
        border-radius: 4px; 
        padding: 5px 5px 5px 10px; 
        background-color: #ffffff;
    } 
    .table td.fit, 
    .table th.fit {
        white-space: nowrap;
        width: 1%;
    }   


        
    legend.legendStyle {
        padding-left: 5px;
        padding-right: 5px;
        min-width: 500px;
    }
    fieldset.fsStyle {
        font-family: Verdana, Arial, sans-serif;
        font-size: small;
        font-weight: normal;
        border: 1px solid #999777;
        padding: 4px;
        margin: 5px;
    }
    legend.legendStyle {
        font-size: 90%;
        color: #880000;
        background-color: transparent;
        font-weight: bold;
    }
    legend {
        width: auto;
        border-bottom: 0px;
    }
</style>

       
    </head>

    <body >
<!-- container -->
    <div class="container">


<!-- For the following code look at footer.php -->