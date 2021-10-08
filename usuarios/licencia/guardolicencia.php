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

$obj = new Consultas('usuarioslicencia');

    if($_POST)
    {
        if(strlen($_POST['codusuarios'])>0){
            $nombres = array();
            $valores = array();
        
            $nombres[] = 'codusuarios';
            $valores[] = $_POST['codusuarios'];


            $nombres[] = 'desde';
            $valores[] = explota($_POST['desde']);
            $nombres[] = 'hasta';
            $valores[] = explota($_POST['hasta']);
            if(strlen($_POST['codlicencia'])>0){
                $obj->Update($nombres, $valores);
                $obj->Where('codlicencia', $_POST['codlicencia']);

            }else{
                $obj->Insert($nombres, $valores);
            }
            $paciente = $obj->Ejecutar();

            if($paciente['estado']=='ok'){
                $oidestudio = '';
                $oidpaciente = '';
                $hace = 'Licencia agregado: '. $codusuarios;
                
                logger($UserID, $oidestudio, $oidpaciente, $hace);
                echo "Licencia agregado con exito";
            }else{
                echo "Fallo al agregar horario, intneta más tarde";
            }
        }
    }

?>