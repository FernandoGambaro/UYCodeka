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

$obj = new Consultas('proyectos');

    if($_POST)
    {
        $nombres = array();
        $valores = array();
    
        $nombres[] = 'codcliente';
        $valores[] = $_POST['codcliente'];

        if(strpos($_POST['fechaini'], '/')>0){
            $nombres[] = 'fechaini';
            $valores[] = explota($_POST['fechaini']);
        }
        if(strpos($_POST['fechafin'], '/')>0){
            $nombres[] = 'fechafin';
            $valores[] = explota($_POST['fechafin']);
        }
        $nombres[] = 'descripcion';
        $valores[] = $_POST['descripcion'];
    
        $obj->Insert($nombres, $valores);
        $paciente = $obj->Ejecutar();

        if($paciente['estado']=='ok'){
            $oidestudio = '';
            $oidpaciente = '';
            $hace = 'Agrega proyecto a'. $codcliente;
            
            logger($UserID, $oidestudio, $oidpaciente, $hace);
            echo 1;
        }else{
            echo 0;
        }

    }

?>