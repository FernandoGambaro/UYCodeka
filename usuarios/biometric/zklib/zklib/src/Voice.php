<?php

namespace ZK;

use ZKLib;

class Voice
{
    /**
     * @param ZKLib $self
     * @param string $voice number
     * @return bool|mixed
     */
    public function get(ZKLib $self, $voice = 0)
    {

        /*
            play test voice:\n
         0 Thank You\n
         1 Incorrect Password\n
         2 Access Denied\n
         3 Invalid ID\n
         4 Please try again\n
         5 Dupicate ID\n
         6 The clock is flow\n
         7 The clock is full\n
         8 Duplicate finger\n
         9 Duplicated punch\n
         10 Beep kuko\n
         11 Beep siren\n
         12 -\n
         13 Beep bell\n
         14 -\n
         15 -\n
         16 -\n
         17 -\n
         18 Windows(R) opening sound\n
         19 -\n
         20 Fingerprint not emolt\n
         21 Password not emolt\n
         22 Badges not emolt\n
         23 Face not emolt\n
         24 Beep standard\n
         25 -\n
         26 -\n
         27 -\n
         28 -\n
         29 -\n
         30 Invalid user\n
         31 Invalid time period\n
         32 Invalid combination\n
         33 Illegal Access\n
         34 Disk space full\n
         35 Duplicate fingerprint\n
         36 Fingerprint not registered\n
         37 -\n
         38 -\n
         39 -\n
         40 -\n
         41 -\n
         42 -\n
         43 -\n
         43 -\n
         45 -\n
         46 -\n
         47 -\n
         48 -\n
         49 -\n
         50 -\n
         51 Focus eyes on the green box\n
         52 -\n
         53 -\n
         54 -\n
         55 -\n

        */
        $self->_section = __METHOD__;
    
        $command = Util::CMD_TESTVOICE;
        $command_string =  pack("I", $voice);
        
		return $self->_command($command, $command_string);
    }
}