<?php
include('cron_class.php');

if(isset($_POST['cron'])) {
	$cron=$_POST['cron'];
	/*Por las dudas introduzcan un código no válido*/
	if (strpos($cron, "rm -")===false){	
		Crontab::addJob($cron);
	}
}
?>