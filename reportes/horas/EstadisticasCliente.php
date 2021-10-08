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
$tipoconsulta=isset($_POST["tipo"]) ? $_POST["tipo"] : "";
$control=0;


if(!isset($_POST['anio'])) {
	$anio=date("Y");
} else {
	$anio=$_POST['anio'];
}

$horasTotales='00:00';
$total=0;
 $pro="[ ";	
for($mes=1;$mes<=12;$mes++) {
	$fechaini=data_first_month_day($anio.'-'.$mes.'-01');
	$fechafin=data_last_month_day($anio.'-'.$mes.'-01');

	//Si no se selecciona cliente reccoro todos
		if($codcliente=='') {
			$totalhoras='00:00';

			//$Descripcion='Todos los clientes activos';
			$objclientes = new Consultas('clientes');
			$objclientes->Select();
			
			$objclientes->Where('borrado', '0');    
			$objclientes->Where('service', '2');    

			$clientes = $objclientes->Ejecutar();
			$total_rows=$clientes["numfilas"];
			$rows = $clientes["datos"];
			if($total_rows>=0){
				foreach($rows as $row){
					
					$Descripcion="Todos los clientes";
					$objhoras = new Consultas('horas');
					$objhoras->Select();
					
					$objhoras->Where('borrado', '0');    
					$objhoras->Where('codcliente', $row['codcliente']);    
					$objhoras->Where("fecha" , $fechaini, '>=');
					$objhoras->Where("fecha" , $fechafin, '<=');
			
					$horas = $objhoras->Ejecutar();
					$total_horas=$horase["numfilas"];
					$rowshoras = $horas["datos"];
					// check if more than 0 record found
					if($total_horas>=0){
						foreach($rowshoras as $rowhoras){
							$parcial=$rowhoras["horas"].':00';
							if($rowhoras["horas"]!='0:00') {
							$totalhoras=SumaHoras($parcial,$totalhoras);
							}
						}
						if($totalhoras!='00:00') {
							$total= round(str_replace(",",".",time2seconds($totalhoras)/60/60), 2);
						}
					}
					$totalhoras='00:00';
				}
			}
		} else {
		/*Para un solo cliente*/
		$totalhoras='00:00';

		//$Descripcion='Todos los clientes activos';
		$objclientes = new Consultas('clientes');
		$objclientes->Select();
		
		$objclientes->Where('codcliente', $codcliente);    
		$objclientes->Where('borrado', '0');    
		$objclientes->Where('service', '2');    

		$clientes = $objclientes->Ejecutar();
		//echo "<br>".$clientes["consulta"]."<br>-->";
		$total_rows=$clientes["numfilas"];
		$rows = $clientes["datos"];
		// check if more than 0 record found
			if($total_rows>=0){
				foreach($rows as $row){
					$Descripcion=" ".$row['nombre']." ".$row['apellido'];
					if($row['empresa']!='') {
						$Descripcion=$row['empresa'];
					}					
					$objhoras = new Consultas('horas');
					$objhoras->Select();
					
					$objhoras->Where('borrado', '0');    
					$objhoras->Where('codcliente', $row['codcliente']);    
					$objhoras->Where("fecha" , $fechaini, '>=');
					$objhoras->Where("fecha" , $fechafin, '<=');
			
					$horas = $objhoras->Ejecutar();
					$total_horas=$horase["numfilas"];
					$rowshoras = $horas["datos"];
					if($total_horas>=0){
						foreach($rowshoras as $rowhoras){
							$parcial=$rowhoras["horas"].':00';
							if($rowhoras["horas"]!='0:00') {
							$totalhoras=SumaHoras($parcial,$totalhoras);
							$horasTotales=SumaHoras($horasTotales,$parcial);
							}
						}
						if($totalhoras!='00:00') {
							$total= round(str_replace(",",".",time2seconds($totalhoras)/60/60), 2);
						}
					}
					$totalhoras='00:00';
				}
			}
		}
		
	if($total<>0) {		   
				$pro.='['.$mes.','.$total;
				$control=1;
	} else {
				$pro.='['.$mes.',0';
	}
	if($mes<12) {
		$pro.='],';
			$control=1;
	} else {
		$pro.=']';
	}
	$total=0;

}
$pro.=" ]";
			$arr['control'] = $control;
			$arr['valu']= $Descripcion;
			$arr['horasTotales']=$horasTotales;
        	$arr['data']= $pro;
        	$arr['tipo']= $tipoconsulta;
        	$arr['datoTxt']= $pro;
        	
	       //$da[] = $arr;
//var_dump($pro);

header("Content-Type: application/json");
echo json_encode($arr);
  
?>