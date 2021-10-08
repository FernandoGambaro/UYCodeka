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

require_once '../../common/fechas.php';   
require_once '../../common/funcionesvarias.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


$codhorasestudio = '';
$codhoraspaciente = '';
$hace = 'Gráfico de horas';

logger($oidcontacto, $codhorasestudio, $codhoraspaciente, $hace);

date_default_timezone_set('America/Montevideo');

$codcliente=isset($_POST["codcliente"]) ? $_POST["codcliente"] : $_GET["codcliente"];
$codusuario=isset($_POST["codusuario"]) ? $_POST["codusuario"] : $_GET["codusuario"];
$tipoconsulta=isset($_POST["tipo"]) ? $_POST["tipo"] : "";
$control=0;


if(!isset($_POST['anio'])) {
	$anio=date("Y");
} else {
	$anio=$_POST['anio'];
}

//Para un usuarios activos
$objusuarios = new Consultas('usuarios');
$objusuarios->Select();
$objusuarios->Where('codusuarios', $codusuario);    
$objusuarios->Where('borrado', '0');    
$usuarios = $objusuarios->Ejecutar();
$total_rowsusuarios=$usuarios["numfilas"];
$rowsusuarios = $usuarios["datos"];
	
if($total_rowsusuarios>=0){
	foreach($rowsusuarios as $rowusuarios){
		$usuario=" ".$rowusuarios['nombre']." ".$rowusuarios['apellido'];

		//$Descripcion='Todos los clientes activos';
		$objclientes = new Consultas('clientes');
		$objclientes->Select();
		if($codcliente<>'0' and $codcliente<>''){
			$objclientes->Where('codcliente', $codcliente);
		}
		$objclientes->Where('borrado', '0');    
		$objclientes->Where('service', '2');    

		$clientes = $objclientes->Ejecutar();
		//echo "<br>".$clientes["consulta"]."<br>-->";
		$total_rows=$clientes["numfilas"];
		$rows = $clientes["datos"];


		//Todos los clientes activos';
		$horasTotales='00:00';
		$pro="[ ";
		/* Para cada cliente calculo la cantidad de horas y las sumo entre todos */
		$contador=0;
		if($total_rows>=0){
			foreach($rows as $row){
				if($codcliente<>'0' and $codcliente<>'' and strlen($row["empresa"])>0 ) {
					$usuario.=" al cliente ".$row["empresa"];
				}
				//$codcliente=$row["codcliente"];
				$proAux='';	
					
				for($mes=1;$mes<=12;$mes++) {
				$totalhoras='00:00';
				$total=0;
				$fechaini=data_first_month_day($anio.'-'.$mes.'-01');
				$fechafin=data_last_month_day($anio.'-'.$mes.'-01');
				
				$objhoras = new Consultas('horas');
				$objhoras->Select();
				
				$objhoras->Where('borrado', '0');    
				$objhoras->Where('codcliente', $row['codcliente']);    
				$objhoras->Where('codusuario', $rowusuarios['codusuarios']);    
				$objhoras->Where("fecha" , $fechaini, '>=');
				$objhoras->Where("fecha" , $fechafin, '<=');

				$horas = $objhoras->Ejecutar();
				$total_horas=$horase["numfilas"];
				$rowshoras = $horas["datos"];
//echo "<br>".$horas["consulta"];
				if($total_horas>=0){	
					foreach($rowshoras as $rowhoras){
						$parcial=$rowhoras["horas"];
						if($rowhoras["horas"]!='0:00') {
						$totalhoras=SumaHoras($parcial,$totalhoras);
						$horasTotales=SumaHoras($horasTotales,$parcial);
						}
					}
					if($totalhoras!='00:00') {
						$total= round(str_replace(",",".",time2seconds($totalhoras)/60/60), 2);
						//$horasTotales=SumaHoras($horasTotales,$total);
					}
				}
					if($total<>0) {		   
						$proAux.='['.$mes.','.$total;
						$control=1;
					} else {
								$proAux.='['.$mes.',0';
					}
					if($mes<12) {
						$proAux.='],';
					} else {
						$proAux.=']';
					}
					$total=0;	
				} //fin recorro meses
					$horasTotales=substr($horasTotales, 0, 5);
					$pro.=' {"label": "&nbsp;'.$row["empresa"].'&nbsp;'.$horasTotales.'hr", "data": ['.$proAux;
					
					if($contador<($total_rows-1)) {
						$pro.=']},';
							$control=1;

					} else {
						$pro.=']}';
					}		
				$codcliente='';
				$contador++;
				$totalhoras='00:00';
				$horasTotales='00:00';	
				
			} 
		}//Fin recorrido clientes
	}
}
$pro.=' ]';


			$arr['control'] = $control;
			$arr['valu']= $usuario;
        	$arr['data']= $pro;
        	$arr['tipo']= $tipoconsulta;
        	$arr['datoTxt']= $pro;
  
header("Content-Type: application/json");
echo json_encode($arr);
  
?>