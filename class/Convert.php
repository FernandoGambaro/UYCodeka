<?php
class JodConverter{
    protected static $_error='';

    public static function getError(){
return self::$_error;
    }

    private static function _getExtension($fileName) {
        return substr(strrchr($fileName, '.'), 1);
    }

    public static function convert($inputFileName,$outputFileName){
if(!file_exists($inputFileName)){
   self::$_error.='Archivo "'.$inputFileName.'" no existe.'."\n";
   return false;
   }
      $inputExt=self::_getExtension($inputFileName);
      $outputExt=self::_getExtension($outputFileName);
      $inputFormat='undef';
      $outputFormat='undef';
      $canConvert=false;
      switch($inputExt){
      case 'odt':
      case 'sxw':
      case 'rtf':
      case 'doc':
      case 'wpd':
      case 'txt':
      case 'html':
      $inputFormat='text';
      break;
      case 'ods':
      case 'sxc':
      case 'xls':
      case 'csv':
      case 'tsv':
      $inputFormat='spreadsheet';
      break;
      case 'odp':
      case 'sxi':
      case 'ppt':
      $inputFormat='presentation';
      break;
      case 'odg':
      $inputFormat='drawing';
      break;
      }
if(in_array($inputFormat,array('text','spreadsheet','presentation'))&&in_array($outputExt,array('pdf','html')))
$canConvert=true;
elseif(in_array($inputFormat,array('drawing','presentation'))&&$outputExt=='swf')
$canConvert=true;
elseif($inputExt==$outputExt){
self::$_error.='No se require conversión.'."\n";
return false;
}else{
switch($outputExt){
   case 'odt':
   case 'sxw':
   case 'rtf':
   case 'doc':
   case 'txt':
   case 'wiki':
   $outputFormat='text';
   break;
   case 'ods':
   case 'sxc':
   case 'xls':
   case 'csv':
   case 'tsv':
   $outputFormat='spreadsheet';
   break;
   case 'odp':
   case 'sxi':
   case 'ppt':
   $outputFormat='presentation';
   break;
   case 'odg':
   $outputFormat='drawing';
   break;
}
$canConvert=($inputFormat==$outputFormat);
}
echo $inputFileName."<br>".$outputFileName."<br>";

        if($canConvert==true)
        {
           //return shell_exec('jodconverter '.$inputFileName.' '.$outputFileName);
            $cmd = 'java -jar '.__DIR__.'/lib/jodconverter-cli-2.2.2.jar ' . $inputFileName . ' ' . $outputFileName;
echo "<br>".$cmd."<br>";
            $ret = shell_exec($cmd);
            $ret = $cmd;
            return $ret;
        }
        else
        {
            self::$_error.='conversion from '.$inputFormat.' format ('.$inputExt.') '.$inputFormat.' format ('.$outputExt.') does not supported.'."\n";
            return false;
        }
    }
}

?>
