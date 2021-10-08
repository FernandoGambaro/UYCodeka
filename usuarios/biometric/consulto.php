<?php
include "zklibrary.php";

echo $ip=isset($_POST['ip']) ? $_POST['ip'] : $_GET['ip'];
$udp_port=isset($_POST['udp_port']) ? $_POST['udp_port'] : $_GET['udp_port'];

$zk = new ZKLibrary($ip, $udp_port);
$zk->connect();
$zk->disableDevice();

$data = $zk->getPlatform($net = true).'*'.$zk->getFirmwareVersion($net = true).'*'.$zk->getSerialNumber($net = true).'*'.$zk->getDeviceName($net = true);
$zk->testVoice();
sleep(1);

$zk->enableDevice();
$zk->disconnect();
    echo json_encode($data);
    flush();          	

?>