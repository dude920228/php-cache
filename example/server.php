<?php

use PhpCache\CacheServer\CacheServer;
use PhpCache\ServiceManager\ConfigAggregator;
use PhpCache\ServiceManager\ServiceManager;

include_once __DIR__.'/../vendor/autoload.php';

$mainConfig = require_once __DIR__.'/../config.php';
$customConfig = require_once __DIR__.'/exampleConfig.php';

$configAggregator = new ConfigAggregator();
$configAggregator->addConfig($mainConfig);
$configAggregator->addConfig($customConfig);
$serviceManager = new ServiceManager($configAggregator->getMergedConfig());

$server = $serviceManager->get(CacheServer::class);
$server->run();
