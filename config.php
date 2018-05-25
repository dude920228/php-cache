<?php

use PhpCache\CacheClient\CacheClient;
use PhpCache\CacheClient\CacheClientFactory;
use PhpCache\CacheServer\CacheServer;
use PhpCache\CacheServer\CacheServerFactory;
use PhpCache\IO\CacheIOHandler;
use PhpCache\IO\CacheIOHandlerFactory;
use PhpCache\Storage\PackageBucket;

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

return array(
    'memory_limit' => 1024,
    'ip' => '127.0.0.1',
    'port' => 9000,
    'bufferSize' => 512,
    'factories' => array(
        CacheServer::class => CacheServerFactory::class,
        CacheIOHandler::class => CacheIOHandlerFactory::class,
        CacheClient::class => CacheClientFactory::class
    ),
    'invokables' => array(
        PackageBucket::class => PackageBucket::class
    )
);
