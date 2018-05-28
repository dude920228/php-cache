<?php
include_once 'vendor/autoload.php';

use PhpCache\CacheServer\CacheServer;
use PhpCache\ServiceManager\ServiceManager;

/* 
 * All rights reserved © 2018 Legow Hosting Kft.
 */
$config = require_once 'config.php';
$serviceManager = new ServiceManager($config);
$server = $serviceManager->get(CacheServer::class);
$server->run();