<?php

use PhpCache\CacheServer\CacheServer;
use PhpCache\ServiceManager\ConfigAggregator;
use PhpCache\ServiceManager\ServiceManager;

/* 
 * All rights reserved © 2018 Legow Hosting Kft.
 */


include_once __DIR__.'/../vendor/autoload.php';

$mainConfig = require_once __DIR__.'/../config.php';
$customConfig = require_once __DIR__.'/exampleConfig.php';

$configAggregator = new ConfigAggregator();
$configAggregator->addConfig($mainConfig);
$configAggregator->addConfig($customConfig);
$serviceManager = new ServiceManager($configAggregator->getMergedConfig());

$server = $serviceManager->get(CacheServer::class);
$server->run();