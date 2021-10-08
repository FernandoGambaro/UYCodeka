<?php

namespace ZK;

use ZKLib;

class SetTimeout
{
    /**
     * @param ZKLib $self
     * @param sec
     * @param usec
     * @return bool|mixed
     */

	public function setTimeout(ZKLib $self, $sec = 0, $usec = 0)
	{
		if($sec != 0)
		{
			$this->timeout_sec = $sec;
		}
		if($usec != 0)
		{
			$this->timeout_usec = $usec;
		}
		$timeout = array('sec'=>$this->timeout_sec, 'usec'=>$this->timeout_usec);
		socket_set_option($self->_zkclient, SOL_SOCKET, SO_RCVTIMEO, $timeout);
	}


}