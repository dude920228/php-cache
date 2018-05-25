<?php
include_once 'vendor/autoload.php';

use PhpCache\CacheServer\CacheServer;

/* 
 * All rights reserved © 2018 Legow Hosting Kft.
 */

$server = new CacheServer();
$server->run("127.0.0.1", "9000");