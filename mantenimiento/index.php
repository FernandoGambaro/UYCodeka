<?php 
$inicio=isset($_GET['inicio']) ? $_GET['inicio'] : '';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Página en mantenimiento</title>
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/jquery.countdown.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
      <div class="wrapper">
           <div class="opacity"></div>
            <div class="gravity">
               <div class="container">
                   <div class=" row">
                       <div class="col-md-12">
                       <img class="logo" src="img/central.png" alt="UyCodeKa">
                       <P>Estamos realizando mantenimiento<br>
                       En breve quedará disponible, disculpe las molestias</p>
                       </div>
                   </div>
               </div>
            </div>
            <div id="countdown"></div>
            <div id="note"></div>

        </div>
    	
		<script src="js/jquery-3.1.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.countdown.js"></script>
        <script type="text/javascript" data-inicio="<?php echo $inicio;?>" src="js/active.js"></script>
    
    
    </body>


</html>