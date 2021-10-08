<?php
include('cron_class.php');

if(isset($_POST['cron'])) {
	$cron=$_POST['cron'];
Crontab::removeJob($cron);
}
?>