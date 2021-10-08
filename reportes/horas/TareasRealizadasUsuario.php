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
require_once __DIR__ .'/../../classes/class_session.php';
require_once __DIR__ .'/../../common/languages.php';

if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
  }

$oidcontacto = $s->data['UserID'] ;
$paleta=isset($s->data['paleta']) ? $s->data['paleta'] : 1;

// set page headers
$page_title = "Nuevo Detalles de horas";

require_once '../../common/fechas.php';   
require_once '../../common/funcionesvarias.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


$codhorasestudio = '';
$codhoraspaciente = '';
$hace = _('Reporte tareas realizadas por usuario');

logger($oidcontacto, $codhorasestudio, $codhoraspaciente, $hace);

$obj = new Consultas('horas');

?>
<html>
	<head>
	   <title><?php _('Tareas realizadas por usuario ');?></title>
<?php 
if(strlen($_GET["codusuario"])>0) { $codusuario=$_GET["codusuario"]; } else { $codusuario="0";}
if(strlen($_GET["fechaini"])>0){ $fechaini=explota($_GET["fechaini"]);}else{ $fechaini=date("Y-m-d");}


$ano=date("Y", strtotime($fechaini));
$mes=date("n", strtotime($fechaini));
$nombremes=array(
    1=>"Enero",
    2=>"Febrero",
    3=>"Marzo",
    4=>"Abril",
    5=>"Mayo",
    6=>"Junio",
    7=>"Julio",
    8=>"Agosto",
    9=>"Septiembre",
    10=>"Octubre",
    11=>"Noviembre",
    12=>"Diciembre"
);

$titulo = $nombremes[$mes].", ".$ano;


	//$Descripcion='Todos los usuarios activos';
	$objusuarios = new Consultas('usuarios');
	$objusuarios->Select();
    if($codusuario <> '0') {
        $objusuarios->Where('codusuarios', $codusuario);    
    } else {
        exit;   
    }
    $objusuarios->Where('estado', '0', '=');   
	//$objusuarios->Where('estado', 'NULL', '=', 'or', ')');    
    $objusuarios->Where('borrado', '0');     
    $usuarios = $objusuarios->Ejecutar();
	$total_rowsusuarios=$usuarios["numfilas"];
    $rowsusuarios = $usuarios["datos"];
    //echo $usuarios["consulta"];

if($total_rowsusuarios>=0){
    foreach($rowsusuarios as $rowusuarios){
        $codusuario=$rowusuarios['codusuarios'];
        $usuario=" ".$rowusuarios['nombre']." ".$rowusuarios['apellido'];
    }
}

if ($mes==2){
    $numerodedias=28;
}
elseif (($mes==4)or ($mes==6) or ($mes==9) or ($mes==11)){
    $numerodedias=30;
}
else{
    $numerodedias=31;
}

$dia=array(
    0=>"Domingo",
    1=>"Lunes",
    2=>"Martes",
    3=>"Mi&eacute;rcoles",
    4=>"Jueves",
    5=>"Viernes",
    6=>"Sábado",
);

 ?>
	   </title> 
        
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../library/estilos/font-awesome.min.css">

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<link href="../../library/bootstrap/bootstrap.css" rel="stylesheet"/>

    <!-- Bootstrap CSS -->
    <script src="../../library/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../library/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../library/js/jquery-ui.min.css" />
    <!-- link rel="stylesheet" href="../pacientes/assets/css/style.css" / -->

<script type="text/javascript" src="../../library/js/jquery.keyz.js"></script>

<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../../library/estilos/customCSS.css" rel="stylesheet">

<link href="../../library/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="../../library/jquery-ui/jquery-ui.js"></script>

<script type="text/javascript" src="../../library/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="../../library/flot/jquery.flot.pie.min.js"></script>
<script type="text/javascript" src="../../library/flot/jquery.flot.stack.min.js"></script>

<link href="../../library/flot/examples/examples.css" rel="stylesheet" type="text/css">
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../common/flot/excanvas.min.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="../../library/flot/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="../../library/flot/jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="../../library/flot/jquery.flot.categories.js"></script>
	<script language="javascript" type="text/javascript" src="../../library/flot/jquery.flot.resize.min.js"></script>
	<script type="text/javascript" src="../../library/flot/jquery.flot.axislabels.js"></script>

<style>
.table {
    width: auto; 
    max-width: 100%;
    margin-bottom: 20px;
}
    </style>

</head> 
<body> 
<div class="container">
    <div class="row">
        <div class="col-xs-12"><?php echo _('Tareas realizadas por '.$usuario.' para el mes de ');

        echo $titulo;

        ?>
        </div>
    </div>
    <div class="row">

    <table class='table table-hover table-responsive table-bordered table-condensed' id="rejilla">
    <thead>
        <tr>
            <th style=" background-color: #5DACCD; color:#fff"><?php echo _('Día');?></th>
            <th style=" background-color: #5DACCD; color:#fff"><?php echo _('Cliente');?></th>
            <th style=" background-color: #5DACCD; color:#fff"><?php echo _('Tarea realizada');?></th>
            <th style=" background-color: #5DACCD; color:#fff"><?php echo _('Horas');?></th>
        </tr>
    </thead>
    <tbody>
<?php
// Genera las filas necesarias para cada mes


$i=1;




while($i<=$numerodedias){
	
$w= date("w",mktime(0,0,0,$mes,$i,$ano));	
$fecha=$ano."-".$mes."-".$i;

	if($w!=0 and $w!=6) {
        $objhoras = new Consultas('horas');
        $objhoras->Select();
        $objhoras->Where('borrado', '0');    
        $objhoras->Where('codusuario', $codusuario);    
        $objhoras->Where("fecha" , $fecha, '=');

        $horas = $objhoras->Ejecutar();
        $total_horas=$horas["numfilas"];
        $rowshoras = $horas["datos"];
        //echo $horas['consulta'];
        $xx=0;
        if($total_horas>0){
            foreach($rowshoras as $rowhoras){
                if($xx==0){
                echo "<tr><td>".implota($fecha)."</td><td>";
                $xx++;
                }else{
                    echo "<tr><td></td><td>";
                }

                $objclientes = new Consultas('clientes');
                $objclientes->Select();
                $objclientes->Where('borrado', '0');    
                $objclientes->Where('codcliente', $rowhoras['codcliente']);    
    
                $clientes = $objclientes->Ejecutar();
                $total_clientes=$clientes["numfilas"];
                $rowsclientes = $clientes["datos"][0];

                $Descripcion=$rowsclientes['nombre']." ".$rowsclientes['apellido'];
                if($rowsclientes['empresa']!='') {
                    $Descripcion=$rowsclientes['empresa'];
                }

                echo $Descripcion;
                $Descripcion='';
                echo "</td><td>";
                echo $rowhoras['descripcion'];
                echo "</td><td>";
                echo $rowhoras['horas'];
                echo "</td></tr>";
                }
        }else{
            echo "<tr><td>".implota($fecha)."</td><td>";
            echo "</td><td> ";
            echo "</td></tr>";

        }
    }
    $i++;
}

?>
</table>
</div>

<script>
	parent.parent.showModal(0);
</script>
</body> 
</html>