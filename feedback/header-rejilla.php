<?php

ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

require_once __DIR__ .'/../classes/class_session.php';
require_once __DIR__ .'/../common/verificopermisos.php';   


if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
  }

  if(((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn'])) or (strlen($s->data['UserTpo'])<=0 and strlen($s->data['UserID'])<=0 ) or
   ( ($s->data['UserTpo']!=2 or $s->data['UserTpo']!=100) and strlen($s->data['UserID'])<=0 ) )
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

  $codcliente=isset($_GET["codcliente"]) ? $_GET["codcliente"] : ''; 


?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../library/bootstrap/bootstrap.min.css" />

    <link rel="stylesheet" href="../library/js/jquery-ui.min.css" />

<link href="../library/bootstrap/bootstrap.css" rel="stylesheet"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <script src="../library/js/OpenWindow-rejilla.js?u=<?php echo time();?>" type="text/javascript"></script>

    <script type="text/javascript" src="../library/js/jquery.keyz.js"></script>

<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../library/estilos/customCSS.css" rel="stylesheet">


<script type="text/javascript">

function showToast(text, tipo){
  parent.showToast(text, tipo);
}



$(document).ready(function(){

    $('.trigger').click(function(e){

    var colaboradorform = $(this).attr("data-colaborador");
    var fechaform = $(this).attr("data-fecha");	
    var codfeedback = $(this).attr("data-codfeedback");	
        parent.document.getElementById("colaboradorform").value=colaboradorform;
        parent.document.getElementById("fechaform").value=fechaform;
        parent.document.getElementById("codfeedback").value=codfeedback;
			  
    }); 


  "use_strict";
    // This array will store the values of the "checked" vehicle checkboxes
  var cboxArray = [];     
  // Check if the vehicle value has already been added to the array and if not - add it
  function itemExistsChecker(cboxValue) {
    var len = cboxArray.length;
    if (len > 0) {
      for (var i = 0; i < len; i++) {
        if (cboxArray[i] == cboxValue) {
          return true;
        }
      }
    }
    cboxArray.push(cboxValue);
  }   
  
$('.checkbox1').each(function(){  
    var cboxValue = $(this).val();
    var cboxChecked = localStorage.getItem(cboxValue) == 'true' ? true : false;
    // On page load check if any of the checkboxes has previously been selected and mark it as "checked"
    if (cboxChecked) {
      $(this).prop('checked', true);
      itemExistsChecker(cboxValue);
    }
        
    // On checkbox change add/remove the vehicle value from the array based on the choice
    $(this).change(function() {
      localStorage.setItem(cboxValue, $(this).is(':checked'));
      if ($(this).is(':checked')) {
        itemExistsChecker(cboxValue);
      } else {
        // Delete the vehicle value from the array if its checkbox is unchecked
        var cboxValueIndex = cboxArray.indexOf(cboxValue);
        if (cboxValueIndex >= 0){
          cboxArray.splice( cboxValueIndex, 1 );
        }
      }
      //console.log(cboxArray);
  $("#seleccionados").val(cboxArray);      
    });
  });
  //console.log(cboxArray);
  $("#seleccionados").val(cboxArray);
});		
</script>
        
</script>

    </head>
    <body >
        <!-- container -->
        <div>
         <!-- For the following code look at footer.php -->