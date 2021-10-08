<html>
<head>
    <title>ZK Test</title>
</head>

<body>
<?php

$url="192.168.1.100";

function availableUrl($host, $port=80, $timeout=10) { 
	$fp = fSockOpen($host, $port, $errno, $errstr, $timeout); 
	return $fp!=false;
}

if(availableUrl($url , '4370')==1) {
echo "Equipo en linea";
}

    date_default_timezone_set('Americas/Montevivideo'); //Default Timezone Of Your Country
    $enableGetDeviceInfo = true;
    $enableGetUsers = true;
    $enableGetData = true;

    include('zklib/ZKLib.php');

    $zk = new ZKLib(
        $url //your device IP
    );

    $ret = $zk->connect();
    if ($ret) {
        $zk->disableDevice();
        $zk->setTime(date('Y-m-d H:i:s')); // Synchronize time
        ?>
        <?php if($enableGetDeviceInfo === true) { ?>
        <table border="1" cellpadding="5" cellspacing="2">
            <tr>
                <td><b>Status</b></td>
                <td>Connected</td>
                <td><b>Version</b></td>
                <td><?php echo($zk->version()); ?></td>
                <td><b>OS Version</b></td>
                <td><?php echo($zk->osVersion()); ?></td>
                <td><b>Platform</b></td>
                <td><?php echo($zk->platform()); ?></td>
            </tr>
            <tr>
                <td><b>Firmware Version</b></td>
                <td><?php echo($zk->fmVersion()); ?></td>
                <td><b>WorkCode</b></td>
                <td><?php echo($zk->workCode()); ?></td>
                <td><b>SSR</b></td>
                <td><?php echo($zk->ssr()); ?></td>
                <td><b>Pin Width</b></td>
                <td><?php echo($zk->pinWidth()); ?></td>
            </tr>
            <tr>
                <td><b>Face Function On</b></td>
                <td><?php echo($zk->faceFunctionOn()); ?></td>
                <td><b>Serial Number</b></td>
                <td><?php echo($zk->serialNumber()); ?></td>
                <td><b>Device Name</b></td>
                <td><?php echo($zk->deviceName()); ?></td>
                <td><b>Get Time</b></td>
                <td><?php echo($zk->getTime()); ?></td>
            </tr>
        </table>
        <?php } ?>
        <hr/>
        <?php if($enableGetUsers === true) { ?>
        <table border="1" cellpadding="5" cellspacing="2" style="float: left; margin-right: 10px;">
            <tr>
                <th colspan="6">Data User</th>
            </tr>
            <tr>
                <th>UID</th>
                <th>ID</th>
                <th>Name</th>
                <th>Card #</th>
                <th>Role</th>
                <th>Password</th>
            </tr>
            <?php
                $numeropin=array();
                $users = $zk->getUser();
                
                foreach ($users as $uItem) {
                    $numeropin[(int)$uItem['userid']] = (int)$uItem['userid'];
                }
                ksort($numeropin);
                echo '<tr><td colspan="5"> Lastpin</td><td> '. array_keys($numeropin)[count($numeropin)-1].'</td></tr>';

                $pin = (int)array_keys($numeropin)[count($numeropin)-1]+1;

            try {
                //echo $zk->removeUser(1118);
                //echo $zk->removeUser(5);

                //$zk->setUser(4, '4', 'Test77343 gg', '110022', ZK\Util::LEVEL_ADMIN, '000000');
                //$zk->setUser(5, '5', 'Maximo'   , '32300', ZK\Util::LEVEL_USER , '000000');
                //$zk->setUser(3, '3', 'User3', '', ZK\Util::LEVEL_USER);
                //$zk->setUser(1118, $pin, 'At EE', '223344', 0);
                /*
                if($ret){
                    $zk->testVoice(0);
                }
                */
                sleep(1);
                

            } catch (Exception $e) {
                header("HTTP/1.0 404 Not Found");
                header('HTTP', true, 500); // 500 internal server error
            }
            try {
            $users = $zk->getUser();
                foreach ($users as $uItem) {
                    ?>
                    <tr>
                        <td><?php echo($uItem['uid']); ?></td>
                        <td><?php echo($uItem['userid']); ?></td>
                        <td><?php echo($uItem['name']); ?></td>
                        <td><?php echo($uItem['cardno']); ?></td>
                        <td><?php echo(ZK\Util::getUserRole($uItem['role'])); ?></td>
                        <td><?php echo($uItem['password']); ?>&nbsp;</td>
                    </tr>
                    <?php
                }
            } catch (Exception $e) {
                header("HTTP/1.0 404 Not Found");
                header('HTTP', true, 500); // 500 internal server error
            }

            //$zk->clearAdmin();
            //$zk->clearUsers();
            //$zk->removeUser(1);
            ?>
        </table>
        <?php } ?>
        <?php if ($enableGetData === true) { ?>
            <table border="1" cellpadding="5" cellspacing="2">
                <tr>
                    <th colspan="7">Data Attendance</th>
                </tr>
                <tr>
                    <th>UID</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>State</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Type</th>
                </tr>
                <?php
                    $attendance = $zk->getAttendance();
                    //var_dump($attendance);
                    if (count($attendance) > 0) {
                        $attendance = array_reverse($attendance, true);
                        sleep(1);
                        foreach ($attendance as $attItem) {
                            ?>
                            <tr>
                                <td><?php echo($attItem['uid']); ?></td>
                                <td><?php echo($attItem['id']); ?></td>
                                <td><?php echo(isset($users[$attItem['id']]) ? $users[$attItem['id']]['name'] : $attItem['id']); ?></td>
                                <td><?php echo(ZK\Util::getAttState($attItem['state'])); ?></td>
                                <td><?php echo(date("d-m-Y", strtotime($attItem['timestamp']))); ?></td>
                                <td><?php echo(date("H:i:s", strtotime($attItem['timestamp']))); ?></td>
                                <td><?php echo(ZK\Util::getAttType($attItem['type'])); ?></td>
                            </tr>
                            <?php
                        }
                    }
                ?>
            </table>
            <?php
                if (count($attendance) > 0) {
                    //$zk->clearAttendance(); // Remove attendance log only if not empty
                }
            ?>
        <?php } ?>
        <?php
        $zk->setTime(date('Y-m-d H:i:s')); // Synchronize time
        $zk->enableDevice();
        //$zk->restart();
        $zk->disconnect();
    }
?>
</body>
</html>
