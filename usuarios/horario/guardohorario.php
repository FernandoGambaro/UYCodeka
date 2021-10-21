<?php
//Defino una variable para cargar solo lo necesario en la seccion que corresponde
//siempre antes de cargar el geader.php
$section='Alta proyecto';

require_once __DIR__ .'/../../common/funcionesvarias.php';
require_once __DIR__ .'/../../classes/class_session.php';
require_once __DIR__ .'/../../common/fechas.php';   


if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
  }
  
  $UserID=$s->data['UserID'];


// isset() is a PHP function used to verify if ID is there or not

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

$obj = new Consultas('horariousuario');

    if($_POST)
    {
        if(strlen($_POST['codusuarios'])>0){
            $nombres = array();
            $valores = array();
        
            $nombres[] = 'codusuarios';
            $valores[] = $_POST['codusuarios'];

            if(strpos($_POST['vigencia'], '/')>0){
                $nombres[] = 'vigencia';
                $valores[] = explota($_POST['vigencia']);
            }
            
            $nombres[] = 'anio';
            $valores[] = date('Y', strtotime($_POST['vigencia']));

            $nombres[] = 'horaingreso';
            $valores[] = $_POST['horaingreso'];
            $nombres[] = 'horasalida';
            $valores[] = $_POST['horasalida'];
            $nombres[] = 'descanso';
            $valores[] = $_POST['descanso'];
            $nombres[] = 'diasemana';
            $valores[] = $_POST['diasemana'];
        
            $obj->Insert($nombres, $valores);
            $paciente = $obj->Ejecutar();

            if($paciente['estado']=='ok'){
                $oidestudio = '';
                $oidpaciente = '';
                $hace = 'Horario agregado: '. $codusuarios;
                
                logger($UserID, $oidestudio, $oidpaciente, $hace);
                echo "Horario agregado con exito";
            }else{
                echo "Fallo al agregar horario, intneta más tarde";
            }
        }
    }

?>