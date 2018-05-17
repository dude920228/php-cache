<?php
include_once 'vendor/autoload.php';
use PhpMQ\MQServer\MQServer;

/* 
 * All rights reserved © 2018 Legow Hosting Kft.
 */

$server = new MQServer();
$server->run("127.0.0.1", "9000");