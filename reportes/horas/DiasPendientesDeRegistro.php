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
$hace = 'Reporte usuarios pendiente de registro';

logger($oidcontacto, $codhorasestudio, $codhoraspaciente, $hace);

$obj = new Consultas('horas');

?>
<html>
	<head>
	   <title>Pendientes de registrar horas mes de
<?php 
if(strlen($_GET["codusuario"])>0) { $codusuario=$_GET["codusuario"]; } else { $codusuario="0";}
if(strlen($_GET["fechaini"])>0){ $fechaini=trim($_GET["fechaini"]);}else{ $fechaini=date("Y-m-d");}

	

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


</head> 
<body> 
<div class="container-fluid">
<div class="table-responsive">
    <table class='table table-hover table-responsive table-bordered table-condensed '>
    <thead class="bg-primary">
        <tr>
            <th colspan="2" class="fit"><?php echo _('Mes: ').genMonth_Text(date("m"));?></th>

        </tr>
    </thead>
    <tbody>

<?php
// Genera las filas necesarias para cada mes

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

$i=1;

	//$Descripcion='Todos los usuarios activos';
	$objusuarios = new Consultas('usuarios');
	$objusuarios->Select();
    if($codusuario <> '0') {
        $objusuarios->Where('codusuarios', $codusuario);    
    } else {
        $objusuarios->Where('tratamiento', '100', '!=');    
        $objusuarios->Where('tratamiento', '2', '!=');    
    }
    $objusuarios->Where('estado', '0', '=');   
	//$objusuarios->Where('estado', 'NULL', '=', 'or', ')');    
    $objusuarios->Where('borrado', '0');     
    $usuarios = $objusuarios->Ejecutar();
	$total_rowsusuarios=$usuarios["numfilas"];
    $rowsusuarios = $usuarios["datos"];
    //echo $usuarios["consulta"];
$diaHoy=date('d');
while($i<=$numerodedias and $i<=$diaHoy){
	
$w= date("w",mktime(0,0,0,$mes,$i,$ano));	
$fecha=$ano."-".$mes."-".$i;

	if($w!=0 and $w!=6) {
		
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }
        $ok=1;

        if($total_rowsusuarios>=0){
            foreach($rowsusuarios as $rowusuarios){
                $codusuario=$rowusuarios['codusuarios'];
                $usuario=" ".$rowusuarios['nombre']." ".$rowusuarios['apellido'];

                $objhoras = new Consultas('horas');
                $objhoras->Select();
                $objhoras->Where('borrado', '0');    
                $objhoras->Where('codusuario', $codusuario);    
                $objhoras->Where("fecha" , $fecha, '=');
                $objhoras->Group('codusuario');

                $horas = $objhoras->Ejecutar();
                $total_horas=$horas["numfilas"];
                //$rowshoras = $horas["datos"];
                if($total_horas<=0){
                    if($ok==1) {
                        echo "<tr class=\"btn-inverse\" >";
                        echo "<td>";                        
                            echo $dia[$w];
                        echo "</td><td>";
                            echo $i;
                        echo "</td></tr>";
                        $ok = 0;
                    }				
                }
            }
            
        }
    }
    $i++;
}
?>
        </tbody>
    </table>

    </div>
</div>
</body> 
</html>