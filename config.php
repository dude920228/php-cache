<?php

use PhpCache\CacheClient\CacheClient;
use PhpCache\CacheClient\CacheClientFactory;
use PhpCache\CacheServer\ActionHandler;
use PhpCache\CacheServer\CacheServer;
use PhpCache\CacheServer\CacheServerFactory;
use PhpCache\IO\CacheIOHandler;
use PhpCache\IO\CacheIOHandlerFactory;
use PhpCache\Storage\Bucket;

return array(
    'memory_limit' => 1024,
    'ip' => '0.0.0.0',
    'port' => 1234,
    'bufferSize' => 8,
    'factories' => array(
        CacheServer::class => CacheServerFactory::class,
        CacheIOHandler::class => CacheIOHandlerFactory::class,
        CacheClient::class => CacheClientFactory::class
    ),
    'invokables' => array(
        Bucket::class => Bucket::class,
        ActionHandler::class => ActionHandler::class
    )
);
