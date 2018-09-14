<?php

use PhpCache\CacheClient\CacheClient;
use PhpCache\CacheClient\CacheClientFactory;
use PhpCache\CacheServer\ActionHandler;
use PhpCache\CacheServer\CacheServer;
use PhpCache\CacheServer\CacheServerFactory;
use PhpCache\IO\CacheIOHandler;
use PhpCache\IO\CacheIOHandlerFactory;
use PhpCache\Storage\Bucket;
use PhpCache\Storage\BucketFactory;
use PhpCache\Storage\Maintainer;
use PhpCache\Storage\MaintainerFactory;

return [
    'config' => [
        'memoryLimit' => 1024, //To be used in future feature: Backing up data to file, after Bucket size exceeds memory limit
        'ip'          => '0.0.0.0',
        'port'        => 9000,
        'bufferSize'  => 256,
        'ttl'         => 3600, // Time To Live -> defines how many seconds the cache should persist an entry (Default 3600)
        'backupTime'  => 1800, // Creates a file storage backup every $backupTime seconds
        'backupDir'   => __DIR__.'/.backup',
    ],
    'services' => [
        'aliases' => [

        ],
        'factories' => [
            CacheServer::class    => CacheServerFactory::class,
            CacheIOHandler::class => CacheIOHandlerFactory::class,
            CacheClient::class    => CacheClientFactory::class,
            Maintainer::class     => MaintainerFactory::class,
            Bucket::class         => BucketFactory::class,
        ],
        'invokables' => [
            ActionHandler::class => ActionHandler::class,
        ],
    ],
];
