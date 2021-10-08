<?php

namespace ZK;

use ZKLib;

class Restart
{
    /**
     * @param ZKLib $self
     * @return bool|mixed
     */
    public function restart(ZKLib $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_RESTART;
        $command_string = chr(0).chr(0);

        return $self->_command($command, $command_string);
    }
}