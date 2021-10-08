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
 
require_once __DIR__ . '/../library/conector/consultas.php';
use App\Consultas;


date_default_timezone_set("America/Montevideo");

function array_envia($array) {
    $tmp = serialize($array);
    $tmp = urlencode($tmp);
    return $tmp;
}
function array_recibe($url_array) {
    $tmp = stripslashes($url_array);
    $tmp = urldecode($tmp);
    $tmp = unserialize($tmp);
   return $tmp;
}
//Libreria de funciones
function par($numero)
{
	$resto = $numero%2;
   if (($resto==0) && ($numero!=0)) {
        return 1;
   } else {
        return 0 ;
   }
}


function logger($oidcontacto, $oidestudio, $oidpaciente, $hace)
{

	$obj = new Consultas('logs');

	if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '' )
	{ $logIP=$_SERVER['HTTP_X_FORWARDED_FOR']; }
	else { $logIP=$_SERVER['REMOTE_ADDR']; }

	if(strlen($oidcontacto)>0){
	$nombresm[] = 'oidcontacto';
	$valoresm[] = $oidcontacto;
	}
	if(strlen($oidestudio)>0){
		$nombresm[] = 'oidestudio';
		$valoresm[] = $oidestudio;
	}
	if(strlen($oidpaciente)>0){
	$nombresm[] = 'oidpaciente';
	$valoresm[] = $oidpaciente;
	}
	
	$nombresm[] = 'fecha';
	$valoresm[] = date("Y-m-d H:i:s");

	$nombresm[] = 'hace';
	$valoresm[] = $hace;
	$nombresm[] = 'ip';
	$valoresm[] = $logIP;


	$obj->Insert($nombresm, $valoresm);
	$med = $obj->Ejecutar();
}



function mes($num){
   if($num>0) {
    /**
     * Creamos un array con los meses disponibles.
     * Agregamos un valor cualquiera al comienzo del array para que los números coincidan
     * con el valor tradicional del mes. El valor "Error" resultará útil
     **/
    $meses = array('Error', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
 
    /**
     * Si el número ingresado está entre 1 y 12 asignar la parte entera.
     * De lo contrario asignar "0"
     **/
    $num_limpio = $num >= 1 && $num <= 12 ? intval($num) : 0;
    return $meses[$num_limpio];
   } else {
    return "-";
   }
}

function mes_avre($num){
   if($num>0) {
    /**
     * Creamos un array con los meses disponibles.
     * Agregamos un valor cualquiera al comienzo del array para que los números coincidan
     * con el valor tradicional del mes. El valor "Error" resultará útil
     **/
    $meses = array('Error', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
        'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
 
    /**
     * Si el número ingresado está entre 1 y 12 asignar la parte entera.
     * De lo contrario asignar "0"
     **/
    $num_limpio = $num >= 1 && $num <= 12 ? intval($num) : 0;
    return $meses[$num_limpio];
   } else {
    return "-";
   }
}



function dia_semana($num)
{ 	
	$dias = array('Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado');
	return $dias[$num];
}
function dia_semana_avr($num)
{
	$dias = array('Dom.','Lun.','Mar.','Mié.','Jue.','Vie.','Sáb.');
	return $dias[$num];
}


function Reemplaza_Acentos($Cadena){
 $Cadena = str_replace('á','&aacute;',$Cadena);
 $Cadena = str_replace('é','&eacute;',$Cadena);
 $Cadena = str_replace('í','&iacute;',$Cadena);
 $Cadena = str_replace('ó','&oacute;',$Cadena);
 $Cadena = str_replace('ú','&uacute;',$Cadena);
 $Cadena = str_replace('Á','&Aacute;',$Cadena);
 $Cadena = str_replace('É','&Eacute;',$Cadena);
 $Cadena = str_replace('Í','&Iacute;',$Cadena);
 $Cadena = str_replace('Ó','&Oacute;',$Cadena);
 $Cadena = str_replace('Ú','&Uacute;',$Cadena);
 $Cadena = str_replace('ñ','&ntilde;',$Cadena);
 $Cadena = str_replace('Ñ','&Ntilde;',$Cadena);
 $Cadena = str_replace('ä','&auml;',$Cadena);
 $Cadena = str_replace('ë','&euml;',$Cadena);
 $Cadena = str_replace('ï','&iuml;',$Cadena);
 $Cadena = str_replace('ö','&ouml;',$Cadena);
 $Cadena = str_replace('ü','&uuml;',$Cadena);
 $Cadena = str_replace('Ä','&Auml;',$Cadena);
 $Cadena = str_replace('Ë','&Euml;',$Cadena);
 $Cadena = str_replace('Ï','&Iuml;',$Cadena);
 $Cadena = str_replace('Ö','&Ouml;',$Cadena);
 $Cadena = str_replace('Ü','&Uuml;',$Cadena);
 $Cadena = str_replace('²','&sup2;',$Cadena);
 $Cadena = str_replace('ñ','&ntilde;',$Cadena);
 $Cadena = str_replace('Ñ','&Ntilde;',$Cadena);
 return $Cadena;
}

function conectado($estado, $USERID, $USERNOM)
{
/*
include("conexion.php");
$result = mysql_query("SELECT * FROM `CONECTADO` WHERE `CONTACTOSID`=$USERID", $conectar);
$num_rows = mysql_num_rows($result);

   if ($estado==1){
      if ($num_rows==0) {
      $sql="INSERT INTO  `CONECTADO` (`CONTACTOSID` ,`CONTACTOSNOMBRE`)VALUES ('$USERID',  '$USERNOM')";
      $result=mysql_query($sql, $conectar) or die(mysql_error($conexion));
      }
   }
   if ($estado==0){
      if ($num_rows!=0) {
      $sql="DELETE FROM `CONECTADO` WHERE `CONTACTOSID` = $USERID";
      $result=mysql_query($sql, $conectar) or die(mysql_error($conexion));
      }
   }
   */
}

function difmes($mesini, $anioini, $mesfin, $aniofin, $diff) {
//Esta funcion debuelve true si la diferencia entre los meses es mayor a la diferencia consultada
if ($aniofin==$anioini) {
   if (($mesfin - $mesini) > $diff){
      return true;
   } else {
      return false;
   }

} elseif ($aniofin>$anioini) {
   if ( (12 - $mesini +$mesfin)>$diff ) {
      return true;
   } else {
      return false;
   }
}

}

function compara_fechas($fecha1,$fecha2)
{
	$dia1="";
	$mes1="";
	$anio1="";
	$dia2="";
	$mes2="";
	$anio2="";

$fecha1 = str_replace("/", "-", $fecha1);
$fecha2 = str_replace("/", "-", $fecha2);
	
	$dias	= (strtotime($fecha1)-strtotime($fecha2))/86400;
	$dias	= abs($dias); $dias = floor($dias);		
	if (strtotime($fecha2) < strtotime($fecha1)) {
		$dias=-$dias;
	}
	return $dias;	
	
}

// function remove_accents()
/**
* Unaccent the input string string. An example string like `ÀØėÿᾜὨζὅБю`
* will be translated to `AOeyIOzoBY`. More complete than :
* strtr( (string)$str,
* "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",
* "aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn" );
*
* @param $str input string
* @param $utf8 if null, function will detect input string encoding
* @return string input string without accent
*/

function remove_accents( $str, $utf8=true )
{
$str = (string)$str;
if( is_null($utf8) ) {
if( !function_exists('mb_detect_encoding') ) {
$utf8 = (strtolower( mb_detect_encoding($str) )=='utf-8');
} else {
$length = strlen($str);
$utf8 = true;
for ($i=0; $i < $length; $i++) {
$c = ord($str[$i]);
if ($c < 0x80) $n = 0; /*# 0bbbbbbb*/
elseif (($c & 0xE0) == 0xC0) $n=1; /*# 110bbbbb*/
elseif (($c & 0xF0) == 0xE0) $n=2; /*# 1110bbbb*/
elseif (($c & 0xF8) == 0xF0) $n=3; /*# 11110bbb*/
elseif (($c & 0xFC) == 0xF8) $n=4; /*# 111110bb*/
elseif (($c & 0xFE) == 0xFC) $n=5; /*# 1111110b*/
else return false; /* Does not match any model*/
for ($j=0; $j<$n; $j++) { /*# n bytes matching 10bbbbbb follow ?*/
if ((++$i == $length)
|| ((ord($str[$i]) & 0xC0) != 0x80)) {
$utf8 = false;
break;
}
}
}
}
}
if(!$utf8)
$str = utf8_encode($str);
 
$transliteration = array(
'Ĳ' => 'I', 'Ö' => 'O','Œ' => 'O','Ü' => 'U','ä' => 'a','æ' => 'a',
'ĳ' => 'i','ö' => 'o','œ' => 'o','ü' => 'u','ß' => 's','ſ' => 's',
'À' => 'A','Á' => 'A','Â' => 'A','Ã' => 'A','Ä' => 'A','Å' => 'A',
'Æ' => 'A','Ā' => 'A','Ą' => 'A','Ă' => 'A','Ç' => 'C','Ć' => 'C',
'Č' => 'C','Ĉ' => 'C','Ċ' => 'C','Ď' => 'D','Đ' => 'D','È' => 'E',
'É' => 'E','Ê' => 'E','Ë' => 'E','Ē' => 'E','Ę' => 'E','Ě' => 'E',
'Ĕ' => 'E','Ė' => 'E','Ĝ' => 'G','Ğ' => 'G','Ġ' => 'G','Ģ' => 'G',
'Ĥ' => 'H','Ħ' => 'H','Ì' => 'I','Í' => 'I','Î' => 'I','Ï' => 'I',
'Ī' => 'I','Ĩ' => 'I','Ĭ' => 'I','Į' => 'I','İ' => 'I','Ĵ' => 'J',
'Ķ' => 'K','Ľ' => 'K','Ĺ' => 'K','Ļ' => 'K','Ŀ' => 'K','Ł' => 'L',
'Ñ' => 'N','Ń' => 'N','Ň' => 'N','Ņ' => 'N','Ŋ' => 'N','Ò' => 'O',
'Ó' => 'O','Ô' => 'O','Õ' => 'O','Ø' => 'O','Ō' => 'O','Ő' => 'O',
'Ŏ' => 'O','Ŕ' => 'R','Ř' => 'R','Ŗ' => 'R','Ś' => 'S','Ş' => 'S',
'Ŝ' => 'S','Ș' => 'S','Š' => 'S','Ť' => 'T','Ţ' => 'T','Ŧ' => 'T',
'Ț' => 'T','Ù' => 'U','Ú' => 'U','Û' => 'U','Ū' => 'U','Ů' => 'U',
'Ű' => 'U','Ŭ' => 'U','Ũ' => 'U','Ų' => 'U','Ŵ' => 'W','Ŷ' => 'Y',
'Ÿ' => 'Y','Ý' => 'Y','Ź' => 'Z','Ż' => 'Z','Ž' => 'Z','à' => 'a',
'á' => 'a','â' => 'a','ã' => 'a','ā' => 'a','ą' => 'a','ă' => 'a',
'å' => 'a','ç' => 'c','ć' => 'c','č' => 'c','ĉ' => 'c','ċ' => 'c',
'ď' => 'd','đ' => 'd','è' => 'e','é' => 'e','ê' => 'e','ë' => 'e',
'ē' => 'e','ę' => 'e','ě' => 'e','ĕ' => 'e','ė' => 'e','ƒ' => 'f',
'ĝ' => 'g','ğ' => 'g','ġ' => 'g','ģ' => 'g','ĥ' => 'h','ħ' => 'h',
'ì' => 'i','í' => 'i','î' => 'i','ï' => 'i','ī' => 'i','ĩ' => 'i',
'ĭ' => 'i','į' => 'i','ı' => 'i','ĵ' => 'j','ķ' => 'k','ĸ' => 'k',
'ł' => 'l','ľ' => 'l','ĺ' => 'l','ļ' => 'l','ŀ' => 'l','ñ' => 'n',
'ń' => 'n','ň' => 'n','ņ' => 'n','ŉ' => 'n','ŋ' => 'n','ò' => 'o',
'ó' => 'o','ô' => 'o','õ' => 'o','ø' => 'o','ō' => 'o','ő' => 'o',
'ŏ' => 'o','ŕ' => 'r','ř' => 'r','ŗ' => 'r','ś' => 's','š' => 's',
'ť' => 't','ù' => 'u','ú' => 'u','û' => 'u','ū' => 'u','ů' => 'u',
'ű' => 'u','ŭ' => 'u','ũ' => 'u','ų' => 'u','ŵ' => 'w','ÿ' => 'y',
'ý' => 'y','ŷ' => 'y','ż' => 'z','ź' => 'z','ž' => 'z','Α' => 'A',
'Ά' => 'A','Ἀ' => 'A','Ἁ' => 'A','Ἂ' => 'A','Ἃ' => 'A','Ἄ' => 'A',
'Ἅ' => 'A','Ἆ' => 'A','Ἇ' => 'A','ᾈ' => 'A','ᾉ' => 'A','ᾊ' => 'A',
'ᾋ' => 'A','ᾌ' => 'A','ᾍ' => 'A','ᾎ' => 'A','ᾏ' => 'A','Ᾰ' => 'A',
'Ᾱ' => 'A','Ὰ' => 'A','ᾼ' => 'A','Β' => 'B','Γ' => 'G','Δ' => 'D',
'Ε' => 'E','Έ' => 'E','Ἐ' => 'E','Ἑ' => 'E','Ἒ' => 'E','Ἓ' => 'E',
'Ἔ' => 'E','Ἕ' => 'E','Ὲ' => 'E','Ζ' => 'Z','Η' => 'I','Ή' => 'I',
'Ἠ' => 'I','Ἡ' => 'I','Ἢ' => 'I','Ἣ' => 'I','Ἤ' => 'I','Ἥ' => 'I',
'Ἦ' => 'I','Ἧ' => 'I','ᾘ' => 'I','ᾙ' => 'I','ᾚ' => 'I','ᾛ' => 'I',
'ᾜ' => 'I','ᾝ' => 'I','ᾞ' => 'I','ᾟ' => 'I','Ὴ' => 'I','ῌ' => 'I',
'Θ' => 'T','Ι' => 'I','Ί' => 'I','Ϊ' => 'I','Ἰ' => 'I','Ἱ' => 'I',
'Ἲ' => 'I','Ἳ' => 'I','Ἴ' => 'I','Ἵ' => 'I','Ἶ' => 'I','Ἷ' => 'I',
'Ῐ' => 'I','Ῑ' => 'I','Ὶ' => 'I','Κ' => 'K','Λ' => 'L','Μ' => 'M',
'Ν' => 'N','Ξ' => 'K','Ο' => 'O','Ό' => 'O','Ὀ' => 'O','Ὁ' => 'O',
'Ὂ' => 'O','Ὃ' => 'O','Ὄ' => 'O','Ὅ' => 'O','Ὸ' => 'O','Π' => 'P',
'Ρ' => 'R','Ῥ' => 'R','Σ' => 'S','Τ' => 'T','Υ' => 'Y','Ύ' => 'Y',
'Ϋ' => 'Y','Ὑ' => 'Y','Ὓ' => 'Y','Ὕ' => 'Y','Ὗ' => 'Y','Ῠ' => 'Y',
'Ῡ' => 'Y','Ὺ' => 'Y','Φ' => 'F','Χ' => 'X','Ψ' => 'P','Ω' => 'O',
'Ώ' => 'O','Ὠ' => 'O','Ὡ' => 'O','Ὢ' => 'O','Ὣ' => 'O','Ὤ' => 'O',
'Ὥ' => 'O','Ὦ' => 'O','Ὧ' => 'O','ᾨ' => 'O','ᾩ' => 'O','ᾪ' => 'O',
'ᾫ' => 'O','ᾬ' => 'O','ᾭ' => 'O','ᾮ' => 'O','ᾯ' => 'O','Ὼ' => 'O',
'ῼ' => 'O','α' => 'a','ά' => 'a','ἀ' => 'a','ἁ' => 'a','ἂ' => 'a',
'ἃ' => 'a','ἄ' => 'a','ἅ' => 'a','ἆ' => 'a','ἇ' => 'a','ᾀ' => 'a',
'ᾁ' => 'a','ᾂ' => 'a','ᾃ' => 'a','ᾄ' => 'a','ᾅ' => 'a','ᾆ' => 'a',
'ᾇ' => 'a','ὰ' => 'a','ᾰ' => 'a','ᾱ' => 'a','ᾲ' => 'a','ᾳ' => 'a',
'ᾴ' => 'a','ᾶ' => 'a','ᾷ' => 'a','β' => 'b','γ' => 'g','δ' => 'd',
'ε' => 'e','έ' => 'e','ἐ' => 'e','ἑ' => 'e','ἒ' => 'e','ἓ' => 'e',
'ἔ' => 'e','ἕ' => 'e','ὲ' => 'e','ζ' => 'z','η' => 'i','ή' => 'i',
'ἠ' => 'i','ἡ' => 'i','ἢ' => 'i','ἣ' => 'i','ἤ' => 'i','ἥ' => 'i',
'ἦ' => 'i','ἧ' => 'i','ᾐ' => 'i','ᾑ' => 'i','ᾒ' => 'i','ᾓ' => 'i',
'ᾔ' => 'i','ᾕ' => 'i','ᾖ' => 'i','ᾗ' => 'i','ὴ' => 'i','ῂ' => 'i',
'ῃ' => 'i','ῄ' => 'i','ῆ' => 'i','ῇ' => 'i','θ' => 't','ι' => 'i',
'ί' => 'i','ϊ' => 'i','ΐ' => 'i','ἰ' => 'i','ἱ' => 'i','ἲ' => 'i',
'ἳ' => 'i','ἴ' => 'i','ἵ' => 'i','ἶ' => 'i','ἷ' => 'i','ὶ' => 'i',
'ῐ' => 'i','ῑ' => 'i','ῒ' => 'i','ῖ' => 'i','ῗ' => 'i','κ' => 'k',
'λ' => 'l','μ' => 'm','ν' => 'n','ξ' => 'k','ο' => 'o','ό' => 'o',
'ὀ' => 'o','ὁ' => 'o','ὂ' => 'o','ὃ' => 'o','ὄ' => 'o','ὅ' => 'o',
'ὸ' => 'o','π' => 'p','ρ' => 'r','ῤ' => 'r','ῥ' => 'r','σ' => 's',
'ς' => 's','τ' => 't','υ' => 'y','ύ' => 'y','ϋ' => 'y','ΰ' => 'y',
'ὐ' => 'y','ὑ' => 'y','ὒ' => 'y','ὓ' => 'y','ὔ' => 'y','ὕ' => 'y',
'ὖ' => 'y','ὗ' => 'y','ὺ' => 'y','ῠ' => 'y','ῡ' => 'y','ῢ' => 'y',
'ῦ' => 'y','ῧ' => 'y','φ' => 'f','χ' => 'x','ψ' => 'p','ω' => 'o',
'ώ' => 'o','ὠ' => 'o','ὡ' => 'o','ὢ' => 'o','ὣ' => 'o','ὤ' => 'o',
'ὥ' => 'o','ὦ' => 'o','ὧ' => 'o','ᾠ' => 'o','ᾡ' => 'o','ᾢ' => 'o',
'ᾣ' => 'o','ᾤ' => 'o','ᾥ' => 'o','ᾦ' => 'o','ᾧ' => 'o','ὼ' => 'o',
'ῲ' => 'o','ῳ' => 'o','ῴ' => 'o','ῶ' => 'o','ῷ' => 'o','А' => 'A',
'Б' => 'B','В' => 'V','Г' => 'G','Д' => 'D','Е' => 'E','Ё' => 'E',
'Ж' => 'Z','З' => 'Z','И' => 'I','Й' => 'I','К' => 'K','Л' => 'L',
'М' => 'M','Н' => 'N','О' => 'O','П' => 'P','Р' => 'R','С' => 'S',
'Т' => 'T','У' => 'U','Ф' => 'F','Х' => 'K','Ц' => 'T','Ч' => 'C',
'Ш' => 'S','Щ' => 'S','Ы' => 'Y','Э' => 'E','Ю' => 'Y','Я' => 'Y',
'а' => 'A','б' => 'B','в' => 'V','г' => 'G','д' => 'D','е' => 'E',
'ё' => 'E','ж' => 'Z','з' => 'Z','и' => 'I','й' => 'I','к' => 'K',
'л' => 'L','м' => 'M','н' => 'N','о' => 'O','п' => 'P','р' => 'R',
'с' => 'S','т' => 'T','у' => 'U','ф' => 'F','х' => 'K','ц' => 'T',
'ч' => 'C','ш' => 'S','щ' => 'S','ы' => 'Y','э' => 'E','ю' => 'Y',
'я' => 'Y','ð' => 'd','Ð' => 'D','þ' => 't','Þ' => 'T','ა' => 'a',
'ბ' => 'b','გ' => 'g','დ' => 'd','ე' => 'e','ვ' => 'v','ზ' => 'z',
'თ' => 't','ი' => 'i','კ' => 'k','ლ' => 'l','მ' => 'm','ნ' => 'n',
'ო' => 'o','პ' => 'p','ჟ' => 'z','რ' => 'r','ს' => 's','ტ' => 't',
'უ' => 'u','ფ' => 'p','ქ' => 'k','ღ' => 'g','ყ' => 'q','შ' => 's',
'ჩ' => 'c','ც' => 't','ძ' => 'd','წ' => 't','ჭ' => 'c','ხ' => 'k',
'ჯ' => 'j','ჰ' => 'h'
);
$str = str_replace( array_keys( $transliteration ),
array_values( $transliteration ),
$str);
return $str;
}
//- remove_accents()

/*/ Calcula la edad (formato: año/mes/dia)*/
function edad($edad){
list($anio,$mes,$dia) = explode("-",$edad);
$anio_dif = date("Y") - $anio;
$mes_dif = date("m") - $mes;
$dia_dif = date("d") - $dia;
if ($dia_dif < 0 || $mes_dif < 0)
$anio_dif--;
return $anio_dif;
}

function extrae($cadena,$num_caracteres){
    //Extracto de los primeros numeros de caracteres definidos en $num_caracteres;
	$cadena_ext = substr($cadena,0, $num_caracteres);
	$tam_cadena = strlen($cadena_ext);
    //Si el extracto ya viene con palabra completa no necesita buscar la siguiente palabra
    if($cadena[$num_caracteres] != " "){
        $sub_cadena = substr($cadena,$num_caracteres, ($tam_cadena - $num_caracteres));
        $miarray = explode (' ', $sub_cadena);
        $res_sub_cadena = $miarray[0];
    }
    $cad = $cadena_ext.$res_sub_cadena;            
    return $cad;   
}

function clean_special_chars( $s, $d=false )
{
    if($d) $s = utf8_decode( $s );

    $chars = array(
    '_' => '/`|´|\^|~|¨|ª|º|©|®/',
    'a' => '/à|á|â|ã|ä|å|æ/', 
    'e' => '/è|é|ê|ë/', 
    'i' => '/ì|í|î|ĩ|ï/',   
    'o' => '/ò|ó|ô|õ|ö|ø/', 
    'u' => '/ù|ú|û|ű|ü|ů/', 
    'A' => '/À|Á|Â|Ã|Ä|Å|Æ/', 
    'E' => '/È|É|Ê|Ë/', 
    'I' => '/Ì|Í|Î|Ĩ|Ï/',   
    'O' => '/Ò|Ó|Ô|Õ|Ö|Ø/', 
    'U' => '/Ù|Ú|Û|Ũ|Ü|Ů/', 
    'c' => '/ć|ĉ|ç/', 
    'C' => '/Ć|Ĉ|Ç/', 
    'n' => '/ñ/', 
    'N' => '/Ñ/', 
    'y' => '/ý|ŷ|ÿ/', 
    'Y' => '/Ý|Ŷ|Ÿ/'
    );

return preg_replace( $chars, array_keys( $chars ), $s );
}

function UTF8_ASCII($str)
{
    return strtr(
        utf8_decode($str),
        utf8_decode('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
        'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy'
    );
}


function remove_accent_2($str){
    $search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
    $replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
    return str_replace($search, $replace, $str);
}

function remove_accent_vi($str) {
    if(!$str) return false;
    $utf8 = array(
                'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
                'd'=>'đ|Đ',
                'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
                'i'=>'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
                'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
                'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
                'y'=>'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    );
    foreach($utf8 as $ascii=>$uni) $str = preg_replace("/($uni)/i",$ascii,$str);
    return $str;
}

function color_inverse($color){
    $color = str_replace('#', '', $color);
    if (strlen($color) != 6){ return '000000'; }
    $rgb = '';
    for ($x=0;$x<3;$x++){
        $c = 255 - hexdec(substr($color,(2*$x),2));
        $c = ($c < 0) ? 0 : dechex($c);
        $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
    }
    return '#'.$rgb;
}



//------		CONVERTIR NUMEROS A LETRAS			  ---------------
//------    Máxima cifra soportada: 18 dígitos con 2 decimales
//------    999,999,999,999,999,999.99 
// NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE BILLONES 
//	NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE MILLONES 
//	NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE PESOS 99/100 M.N.
//------		Creada por:                 	     ---------------
//------       		ULTIMINIO RAMOS GALÁN  	  ---------------
//------            uramos@gmail.com 			  ---------------
//------		10 de junio de 2009. México, D.F.  ---------------
//------		PHP Version 4.3.1 o mayores (aunque podría funcionar en versiones anteriores, tendrías que probar)
function numtoletras($xcifra)
{ 
$xarray = array(0 => "Cero",
1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", 
"DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE", 
"VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA", 
100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
);
//
$xcifra = trim($xcifra);
$xlength = strlen($xcifra);
$xpos_punto = strpos($xcifra, ".");
$xaux_int = $xcifra;
$xdecimales = "00";
if (!($xpos_punto === false))
	{
	if ($xpos_punto == 0)
		{
		$xcifra = "0".$xcifra;
		$xpos_punto = strpos($xcifra, ".");
		}
	$xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
	$xdecimales = substr($xcifra."00", $xpos_punto + 1, 2); // obtengo los valores decimales
	}

$XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
$xcadena = "";
for($xz = 0; $xz < 3; $xz++)
	{
	$xaux = substr($XAUX, $xz * 6, 6);
	$xi = 0; $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
	$xexit = true; // bandera para controlar el ciclo del While	
	while ($xexit)
		{
		if ($xi == $xlimite) // si ya llegó al límite m&aacute;ximo de enteros
			{
			break; // termina el ciclo
			}
	
		$x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
		$xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
		for ($xy = 1; $xy < 4; $xy++) // ciclo para revisar centenas, decenas y unidades, en ese orden
			{
			switch ($xy) 
				{
				case 1: // checa las centenas
					if (substr($xaux, 0, 3) < 100) // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
						{
						}
					else
						{
						$xseek = @$xarray[substr($xaux, 0, 3)]; // busco si la centena es número redondo (100, 200, 300, 400, etc..)
						if ($xseek)
							{
							$xsub = @subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
							if (substr($xaux, 0, 3) == 100) 
								$xcadena = " ".$xcadena." CIEN ".$xsub;
							else
								$xcadena = " ".$xcadena." ".$xseek." ".$xsub;
							$xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
							}
						else // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
							{
							$xseek = $xarray[substr($xaux, 0, 1) * 100]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
							$xcadena = " ".$xcadena." ".$xseek;
							} // ENDIF ($xseek)
						} // ENDIF (substr($xaux, 0, 3) < 100)
					break;
				case 2: // checa las decenas (con la misma lógica que las centenas)
					if (substr($xaux, 1, 2) < 10)
						{
						}
					else
						{
						$xseek = @$xarray[substr($xaux, 1, 2)];
						if ($xseek)
							{
							$xsub = @subfijo($xaux);
							if (substr($xaux, 1, 2) == 20)
								$xcadena = " ".$xcadena." VEINTE ".$xsub;
							else
								$xcadena = " ".$xcadena." ".$xseek." ".$xsub;
							$xy = 3;
							}
						else
							{
							$xseek = $xarray[substr($xaux, 1, 1) * 10];
							if (substr($xaux, 1, 1) * 10 == 20)
								$xcadena = " ".$xcadena." ".$xseek;
							else	
								$xcadena = " ".$xcadena." ".$xseek." Y ";
							} // ENDIF ($xseek)
						} // ENDIF (substr($xaux, 1, 2) < 10)
					break;
				case 3: // checa las unidades
					if (substr($xaux, 2, 1) < 1) // si la unidad es cero, ya no hace nada
						{
						}
					else
						{
						$xseek = $xarray[substr($xaux, 2, 1)]; // obtengo directamente el valor de la unidad (del uno al nueve)
						$xsub = subfijo($xaux);
						$xcadena = " ".$xcadena." ".$xseek." ".$xsub;
						} // ENDIF (substr($xaux, 2, 1) < 1)
					break;
				} // END SWITCH
			} // END FOR
			$xi = $xi + 3;
		} // ENDDO

		if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
			$xcadena.= " DE";
			
		if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
			$xcadena.= " DE";
		
		// ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
		if (trim($xaux) != "")
			{
			switch ($xz)
				{
				case 0:
					if (trim(substr($XAUX, $xz * 6, 6)) == "1")
						$xcadena.= "UN BILLON ";
					else
						$xcadena.= " BILLONES ";
					break;
				case 1:
					if (trim(substr($XAUX, $xz * 6, 6)) == "1")
						$xcadena.= "UN MILLON ";
					else
						$xcadena.= " MILLONES ";
					break;
				case 2:
					if ($xcifra < 1 )
						{
						$xcadena = "CERO con $xdecimales/100";
						}
					if ($xcifra >= 1 && $xcifra < 2)
						{
						$xcadena = "UN con $xdecimales/100 ";
						}
					if ($xcifra >= 2)
						{
						$xcadena.= " con $xdecimales/100"; // 
						}
					break;
				} // endswitch ($xz)
			} // ENDIF (trim($xaux) != "")
		// ------------------      en este caso, para México se usa esta leyenda     ----------------
		$xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
		$xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles 
		$xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
		$xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles 
		$xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
		$xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
		$xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
	} // ENDFOR	($xz)
	return trim($xcadena);
} // END FUNCTION


function subfijo($xx)
	{ // esta función regresa un subfijo para la cifra
	$xx = trim($xx);
	$xstrlen = strlen($xx);
	if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
		$xsub = "";
	//	
	if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
		$xsub = "MIL";
	//
	return $xsub;
	} // END FUNCTION
	
function RandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}	
	
function checkRut($rut) {

	if (strlen($rut) != 12 || !is_numeric($rut))

		return false;

	$dc = substr($rut, 11, 1);

	$rut = substr($rut, 0, 11);

	$total = 0;

	$factor = 2;

	for ($i = 10; $i >= 0; $i--) {

		$total += ($factor * substr($rut, $i, 1));

		$factor = ($factor == 9)?2:++$factor;

	}

	$dv = 11 - ($total % 11);

	if ($dv == 11)

		$dv = 0;

	elseif ($dv == 10)

		$dv = 1;

	return $dv == $dc;

}

function escape($value)
    {
        $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
        $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

        return str_replace($search, $replace, $value);
	}

	
	function load_image($filename, $type) {
		if( $type == IMAGETYPE_JPEG ) {
			$image = imagecreatefromjpeg($filename);
		}
		elseif( $type == IMAGETYPE_PNG ) {
			$image = imagecreatefrompng($filename);
		}
		elseif( $type == IMAGETYPE_GIF ) {
			$image = imagecreatefromgif($filename);
		}
		return $image;
	}
	
	function resize_image($new_width, $new_height, $image, $width, $height) {
		$new_image = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		return $new_image;
	}
	
	function resize_image_to_width($new_width, $image, $width, $height) {
		$ratio = $new_width / $width;
		$new_height = $height * $ratio;
		return resize_image($new_width, $new_height, $image, $width, $height);
	}
	
	function resize_image_to_height($new_height, $image, $width, $height) {
		$ratio = $new_height / $height;
		$new_width = $width * $ratio;
		return resize_image($new_width, $new_height, $image, $width, $height);
	}
	
	function scale_image($scale, $image, $width, $height) {
		$new_width = $width * $scale;
		$new_height = $height * $scale;
		return resize_image($new_width, $new_height, $image, $width, $height);
	}
	
	
	function save_image($new_image, $new_filename, $new_type='jpeg', $quality=80) {
		if( $new_type == 'jpeg' ) {
			imagejpeg($new_image, $new_filename, $quality);
		 }
		 elseif( $new_type == 'png' ) {
			imagepng($new_image, $new_filename);
		 }
		 elseif( $new_type == 'gif' ) {
			imagegif($new_image, $new_filename);
		 }
	}
?>