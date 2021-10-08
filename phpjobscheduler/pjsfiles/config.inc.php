<?php
// ---------------------------------------------------------
 $app_name = "phpJobScheduler";
 $phpJobScheduler_version = "3.9";
// ---------------------------------------------------------
include (realpath(dirname(__FILE__))."/../../configuro.php"); 

define('DBHOST', 'localhost');// database host address - localhost is usually fine

define('DBNAME', $BaseDeDatos);// database name - must already exist
define('DBUSER', $Usuario);// database username - must already exist
define('DBPASS', $Password);// database password for above username

define('DEBUG', false);// set to false when done testing