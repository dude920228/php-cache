<?php

ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php-cache.log');
require_once 'vendor/autoload.php';

use PhpCache\CacheServer\CacheServer;
use PhpCache\ServiceManager\ConfigAggregator;
use PhpCache\ServiceManager\ServiceManager;

$config = include_once 'config.php';
$configAggregator = new ConfigAggregator();
$configAggregator->addConfig($config);
$serviceManager = new ServiceManager($configAggregator->getMergedConfig());
$server = $serviceManager->get(CacheServer::class);
$server->run();
