<?php

use PhpCache\CacheClient\CacheClient;
use PhpCache\CacheClient\CacheClientFactory;
use PhpCache\CacheServer\ActionHandler;
use PhpCache\CacheServer\CacheServer;
use PhpCache\CacheServer\CacheServerFactory;
use PhpCache\IO\CacheIOHandler;
use PhpCache\IO\CacheIOHandlerFactory;
use PhpCache\Storage\Bucket;
use PhpCache\Storage\Maintainer;
use PhpCache\Storage\MaintainerFactory;

return array(
    'memory_limit' => 1024,
    'ip' => '0.0.0.0',
    'port' => 1234,
    'bufferSize' => 8,
    'ttl' => 3600, // Time To Live -> defines how many seconds the cache should persist an entry (Default 3600)
    'backupTime' => 5, // Creates a file storage backup every $backup seconds
    'backupDir' => __DIR__.'/.backup',
    'factories' => array(
        CacheServer::class => CacheServerFactory::class,
        CacheIOHandler::class => CacheIOHandlerFactory::class,
        CacheClient::class => CacheClientFactory::class,
        Maintainer::class => MaintainerFactory::class,
    ),
    'invokables' => array(
        Bucket::class => Bucket::class,
        ActionHandler::class => ActionHandler::class,
    )
);
