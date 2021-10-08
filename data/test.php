<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache'); 
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('America/Montevideo');


echo "<!DOCTYPE html PUBLIC -//W3C//DTD XHTML 1.0 Strict//EN http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd>
      <html xmlns=http://www.w3.org/1999/xhtml>
      <head>
      <meta http-equiv=Content-Type content=text/html; charset=UTF-8 />
      <title>Title</title>
      <link href=style.css rel=stylesheet type=text/css>
      </head>
      <body topmargin=0 leftmargin=0>";
echo '<font color=blue>';

echo '------------------------------------------------------------- <br>';
echo 'Inicio el procesamiento de archivos<br>';
echo 'Este proceso tarda varios minutos<br>';
echo '-------------------------------------------------------------<br>';
echo date('h:i:s') . "\n<br>";


$fd = array(
0 => array("pipe", "r"),  // stdin
1 => array("pipe", "w"),  // stdout
2 => array("file", "error.txt", "a") // stderr
);

$pipes = array();

$script=proc_open("/home/fernando/ownCloud/www/reciboslogo/data/pasodatos.sh Sueldos072016.rar --foo=1 &", $fd, $pipes);


echo "<br>............1. -> ".$pid=proc_get_status($script)['pid'];
echo "<br>............2. -> ".proc_get_status($script)['running'];
echo "<br>............3. -> ".proc_get_status($script)['command'];
echo "<br>";

$pid="";
$sigo=true;



	//exec("ps -C pasodatos.sh", $output, $return_var);
/*	
$output = shell_exec('ps -C pasodatos.sh');
while(strpos($output, "pasodatos.sh")!==false) { 
	echo "-";
	
	$output = shell_exec('ps -C pasodatos.sh');
}	


/*
while(true) {
    // detect if the child has terminated - the php way
     $status = proc_get_status($script);
     var_dump($status);
     
    // check retval
    if($status === FALSE) {
        throw new Exception ("Failed to obtain status information for $pid");
    }
    
    if($status['running'] === FALSE) {
        $exitcode = $status['exitcode'];
        $pid = -1;
        echo "child exited with code: $exitcode\n";
        exit($exitcode);
	}
	echo "*";
}
*/
//$output = shell_exec('/home/fernando/ownCloud/www/reciboslogo/data/pasodatos.sh');
//echo "<pre>".$output."</pre>";
echo '</font><p>';
echo '</body>';
echo '</html>';

/*
class BackgroundProcess
{
    private $command;
    private $pid;

    public function __construct($command)
    {
        $this->command = $command;
    }

    public function run($outputFile = '&> /dev/null &')
    {
//        $this->pid = shell_exec(sprintf('%s 2>&1 & echo $!', $this->command, $outputFile));
        $this->pid = shell_exec('nohup nice -n 10 sleep 30 & ');
    }

    public function isRunning()
    {
        try {
            $result = shell_exec(sprintf('ps %d', $this->pid));
            if(count(preg_split("/\n/", $result)) > 2) {
                return true;
            }
        } catch(Exception $e) {}

        return false;
    }

    public function getPid()
    {
        return $this->pid;
    }
}

//use Bc\BackgroundProcess\BackgroundProcess;

//$process = new BackgroundProcess('/home/fernando/ownCloud/www/reciboslogo/data/pasodatos.sh');
$process = new BackgroundProcess('sleep 35');
$process->run();

echo sprintf('Crunching numbers in process %d', $process->getPid());

while ($process->isRunning()) {
    echo '.';
    sleep(1);
}
echo "<br>\nDone.\n"
*/
?>