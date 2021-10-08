<?php

/*
http://www.kavoir.com/2011/10/php-crontab-class-to-add-and-remove-cron-jobs.html
Provided that your user account on the server has the privileges to access crontab thus can create or r
emove cron jobs, you can use this PHP class to integrate crontab in your application. 
I created it for many of my own projects that need crontab to do scheduled jobs. It’s pretty straightforward.

Don’t know what crontab is and how it works? Read here and here. 
With this class, you or your users can easily set up crontab jobs and automate tasks by schedule with the web interface.


You may well ignore the private methods that do the internal chores. And keep in mind that any cron job is a text string.

getJobs() – returns an array of existing / current cron jobs. Each array item is a string (cron job).
saveJobs($jobs = array()) – save the $jobs array of cron jobs into the crontab so they would be run by the server. All existing jobs in crontab are erased and replaced by $jobs.
doesJobExist($job = ”) – check if a specific job exist in crontab.
addJob($job = ”) – add a cron job to the crontab.
removeJob($job = ”) – remove a cron job from crontab.

Modificamos el "/r/n" por PHP_EOL para evitar el salto de línea que lo crea como un caracter más y da error en la ejecución 
*/



class Crontab {

    static private function stringToArray($jobs = '') {
        $array = explode(PHP_EOL, trim($jobs)); // trim() gets rid of the last \r\n
        foreach ($array as $key => $item) {
            if ($item == '') {
                unset($array[$key]);
            }
        }
        return $array;
    }

    static private function arrayToString($jobs = array()) {
        $string = implode(PHP_EOL, $jobs);
        return $string;
    }

    static public function getJobs() {
        $output = shell_exec('crontab -l');
        return self::stringToArray($output);
    }

    static public function saveJobs($jobs = array()) {
        $output = shell_exec('echo "'.self::arrayToString($jobs).'" | crontab -');
        return $output; 
    }

    static public function doesJobExist($job = '') {
        $jobs = self::getJobs();
        if (in_array($job, $jobs)) {
            return true;
        } else {
            return false;
        }
    }

    static public function addJob($job = '') {
        if (self::doesJobExist($job)) {
            return false;
        } else {
            $jobs = self::getJobs();
            $jobs[] = $job;
            return self::saveJobs($jobs);
        }
    }

    static public function removeJob($job = '') {
        if (self::doesJobExist($job)) {
            $jobs = self::getJobs();
            unset($jobs[array_search($job, $jobs)]);
            return self::saveJobs($jobs);
        } else {
            return false;
        }
    }

}
?>