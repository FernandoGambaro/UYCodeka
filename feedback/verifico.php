<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

	$arr=array();
	$arr['fechafeedback'] ='';
	$arr['fechaproxima'] ='';

	if(isset($_POST['fecha'])) {
		$fecha=explota($_POST['fecha']);
		$colaborador=$_POST['colaborador'];
		$codformulario=$_POST['codformulario'];
		$query="SELECT * FROM feedback WHERE  `feedback`.`fecha`='".$fecha."' AND `feedback`.`colaborador`='".$colaborador."' 
		AND `feedback`.`codformulario` = '".$codformulario."' AND `feedback`.`borrado`=0";			
		$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if(mysqli_num_rows($rs_query)>0){
        	$arr['erro'] = '1';        	
        	$arr['query']=$query;
//	      $data[] = $arr;
		}else {
		$query="SELECT * FROM feedback WHERE  `feedback`.`colaborador`='".$colaborador."' 
		AND `feedback`.`codformulario` = '".$codformulario."' AND `feedback`.`borrado`=0 
		GROUP BY feedback.fecha, feedback.colaborador, feedback.codformulario ORDER BY feedback.fecha DESC LIMIT 1";
		$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
			if(mysqli_num_rows($rs_query)>0){
				$arr['fechafeedback'] =mysqli_result($rs_query,0, "fechafeedback");
				$arr['fechaproxima'] =mysqli_result($rs_query,0, "fechaproxima");
			}
        	$arr['erro'] = 0;
//	      $data[] = $arr;
		}
}

    echo json_encode($arr);
    flush();    

?>