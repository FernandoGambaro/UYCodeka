<?php

namespace ZK;

use ZKLib;

class Ping
{
    /**
     * @param ZKLib $self
     * @return bool|mixed
     */

	public function ping(ZKLib $self, $timeout = 1)
	{
        $time1 = microtime(true);
        //$pfile = socket_sendto($self->_zkclient, $buf, strlen($buf), 0, $self->_ip, $self->_port);
		$pfile = fsockopen($self->_ip, $self->_port, $errno, $errstr, $timeout);
		if(!$pfile)
		{
			return 'down: '.$self->_ip. ' port '. $self->_port;
		}
		$time2 = microtime(true);
		fclose($pfile);
		return round((($time2 - $time1) * 1000), 0);
	}
}