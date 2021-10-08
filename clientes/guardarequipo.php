<?php
//Defino una variable para cargar solo lo necesario en la seccion que corresponde
//siempre antes de cargar el geader.php
$section='Alta proyecto';

require_once __DIR__ .'/../common/funcionesvarias.php';
require_once __DIR__ .'/../classes/class_session.php';
require_once __DIR__ .'/../common/fechas.php';   


if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
  }
  
  $UserID=$s->data['UserID'];


// isset() is a PHP function used to verify if ID is there or not

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

$obj = new Consultas('equipos');

    if($_POST)
    {
        $nombres = array();
        $valores = array();
    
        $nombres[] = 'codcliente';
        $valores[] = $_POST['codcliente'];

        if(strpos($_POST['fecha'], '/')>0){
            $nombres[] = 'fecha';
            $valores[] = explota($_POST['fecha']);
        }
        //alias; numero; service ;detalles; diasrespaldo     
        $nombres[] = 'descripcion';
        $valores[] = $_POST['descripcion'];
        $nombres[] = 'alias';
        $valores[] = $_POST['alias'];
        $nombres[] = 'numeron';
        $valores[] = $_POST['numero'];
        $nombres[] = 'service';
        $valores[] = $_POST['service'];
        $nombres[] = 'detalles';
        $valores[] = $_POST['detalles'];
        $nombres[] = 'diasrespaldo';
        $valores[] = $_POST['diasrespaldo'];
    
        $obj->Insert($nombres, $valores);
        $paciente = $obj->Ejecutar();

        if($paciente['estado']=='ok'){
            $oidestudio = '';
            $oidpaciente = '';
            $hace = 'Agrega equipo a'. $codcliente;
            
            logger($UserID, $oidestudio, $oidpaciente, $hace);
            echo 1;
        }else{
            echo 0;
        }

    }

?>