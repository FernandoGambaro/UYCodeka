<?php
date_default_timezone_set("America/Montevideo"); 

function implota($fecha) /*/ bd2local*/
{
	if (($fecha == "") || ($fecha == "0000-00-00"))
		return "";
	$vector_fecha = explode("-",$fecha);
	$aux = @$vector_fecha[2];
	$vector_fecha[2] = $vector_fecha[0];
	$vector_fecha[0] = $aux;
	return implode("/",$vector_fecha);
}

function explota($fecha) /*/ local2bd*/
{
	$vector_fecha = explode("/",$fecha);
	$aux = @$vector_fecha[2];
	$vector_fecha[2] = $vector_fecha[0];
	$vector_fecha[0] = $aux;
	return implode("-",$vector_fecha);
};

/** Actual month last day **/
  function data_last_month_day($fecha) { 
      $month = date('m',strtotime($fecha));
      $year = date('Y',strtotime($fecha));
      $day = date("d", mktime(0,0,0, $month+1, 0, $year));
 
      return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
  };
  /** Actual month first day **/
  function data_first_month_day($fecha) {
      $month = date('m',strtotime($fecha));
      $year = date('Y',strtotime($fecha));
      return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
  }

function genMonth_Text($m) {
	$month_text='';
 switch ($m) {
  case 1: $month_text = "Enero"; break;
  case 2: $month_text = "Febrero"; break;
  case 3: $month_text = "Marzo"; break;
  case 4: $month_text = "Abril"; break;
  case 5: $month_text = "Mayo"; break;
  case 6: $month_text = "Junio"; break;
  case 7: $month_text = "Julio"; break;
  case 8: $month_text = "Agosto"; break;
  case 9: $month_text = "Septiembre"; break;
  case 10: $month_text = "Octubre"; break;
  case 11: $month_text = "Noviembre"; break;
  case 12: $month_text = "Diciembre"; break;
 }
 return ($month_text);
}   
  
function genMonthAb_Text($m) {
 switch ($m) {
  case 1: $month_text = "Ene"; break;
  case 2: $month_text = "Feb"; break;
  case 3: $month_text = "Mar"; break;
  case 4: $month_text = "Abr"; break;
  case 5: $month_text = "May"; break;
  case 6: $month_text = "Jun"; break;
  case 7: $month_text = "Jul"; break;
  case 8: $month_text = "Ago"; break;
  case 9: $month_text = "Sep"; break;
  case 10: $month_text = "Oct"; break;
  case 11: $month_text = "Nov"; break;
  case 12: $month_text = "Dic"; break;
 }
 return ($month_text);
}   

function diasemana($date) {
	$days = array('Domingo', 'Lunes', 'Martes', 'Miércoles','Jueves','Viernes', 'Sábado');
	$day=date("w", strtotime($date));
	return $days[$day];
}

function SumaHoras($hora1,$hora2) {
$hora1=explode(":",$hora1);
$hora2=explode(":",$hora2);
$horas=@(int)$hora1[0]+@(int)$hora2[0];
$minutos=@(int)$hora1[1]+@(int)$hora2[1];
$segundos=@(int)$hora1[2]+@(int)$hora2[2];
$horas+=@(int)($minutos/60);
$minutos=@(int)($minutos%60)+@(int)($segundos/60);
$segundos=@(int)($segundos%60);
return (intval($horas)< 10 ? '0'.intval($horas):intval($horas)).':'.($minutos < 10 ?'0'.$minutos:$minutos).':'.($segundos < 10 ? '0'.$segundos:$segundos);
}

function RestaHoras($horafin,$horaini){
	$horai=substr($horaini,0,2);
	$mini=substr($horaini,3,2);
	$segi=substr($horaini,6,2);

	$horaf=substr($horafin,0,2);
	$minf=substr($horafin,3,2);
	$segf=substr($horafin,6,2);

	$ini=((($horai*60)*60)+($mini*60)+$segi);
	$fin=((($horaf*60)*60)+($minf*60)+$segf);

	$dif=$fin-$ini;

	$difh=floor($dif/3600);
	$difm=floor(($dif-($difh*3600))/60);
	$difs=$dif-($difm*60)-($difh*3600);

	return date("H:i:s",mktime($difh,$difm,$difs));
}

function time2seconds($time='00:00:00')
{
    list($hours, $mins, $secs) = explode(':', $time);
    return ($hours * 3600 ) + ($mins * 60 ) + $secs;
}	
	
?>