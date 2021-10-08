<?php
 
 // isset() is a PHP function used to verify if ID is there or not
 $codcliente = isset($_GET['codcliente']) ? $_GET['codcliente'] : $_POST['codcliente'];
  
 require_once __DIR__ .'/../library/conector/consultas.php';
 use App\Consultas;


 $obj = new Consultas('clientes');
 
 $obj->Select();
 $obj->Where('codcliente', $codcliente);
 $paciente = $obj->Ejecutar();
 $paciente = $paciente["datos"][0];
 
 $provincias = new Consultas('departamentos');
 $provincias->Select();
 $provincias->Where('departamentosid', $paciente['codprovincia']);
 $provincias = $provincias->Ejecutar();
 $provincias = $provincias["datos"][0];


$direccion=$paciente["direccion"]. "," .$paciente["localidad"].", "._('Departamento de')." ". $provincias["departamentosdesc"]." , UY ";


?>
<!DOCTYPE html>
<html>
  <head>
    <title>Dirección Cliente</title>
    <meta charset="UTF-8">
    <link href="../estilos/estilos.css" type="text/css" rel="stylesheet">


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

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../library/estilos/font-awesome.min.css">
<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../library/estilos/customCSS.css" rel="stylesheet">
    

    <link rel="stylesheet" type="text/css" href="../map/styles.css" />


    <script src="https://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="../map/jquery.geocomplete.js"></script>

<style type='text/css'>
#geocomplete { 
    width: 200px;
    }

.map_canvas { 
    width: 500px; 
    height: 400px; 
    margin: 0 auto; 
    }

</style>

  </head>
  <body>

  <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo $paciente["nombre"]." ".$paciente["apellido"]." - ". $paciente["empresa"];?></legend>

<iframe frameborder="0" src="https://maps.google.com/maps?f=q&source=s_q&hl=es&geocode=&q=' <?php echo str_replace(",", "", str_replace(" ", "+", $direccion)) ;?>'&z=14&output=embed"
 width="100%" height="400"></iframe>
    <form>
      <input id="geocomplete" style='width:45em' value="<?php echo $direccion;?>" />
      <input id="find" type="button" value="find"  style="display:none;"/>
          
      <a id="reset" href="#" style="display:none;">&nbsp;</a>
    </form>
<div id="logs">
</div>
   
</fieldset>      
    
  </body>
</html>

