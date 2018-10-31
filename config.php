<?php

use PhpCache\CacheClient\CacheClient;
use PhpCache\CacheClient\CacheClientFactory;
use PhpCache\CacheEventListener\CacheEventListener;
use PhpCache\CacheEventListener\CacheEventListenerFactory;
use PhpCache\CacheEventListener\CacheEventListenerInterface;
use PhpCache\CacheServer\ActionHandler;
use PhpCache\CacheServer\CacheServer;
use PhpCache\CacheServer\CacheServerFactory;
use PhpCache\IO\CacheIOHandler;
use PhpCache\IO\CacheIOHandlerFactory;
use PhpCache\Logger\CacheDataLogger;
use PhpCache\Logger\CacheDataLoggerFactory;
use PhpCache\Storage\Bucket;
use PhpCache\Storage\BucketFactory;
use PhpCache\Storage\Maintainer;
use PhpCache\Storage\MaintainerFactory;

return [
    'config' => [
        'memoryLimit'       => 1024,
        'location'          => __DIR__.'/temp/php-cache.sock',
        'port'              => 9000,
        'bufferSize'        => 256,
        'ttl'               => 3600,
        'backupTime'        => 1800,
        'backupDir'         => __DIR__.'/.backup',
        'socketType'        => CacheIOHandler::SOCKET_TYPE_FILE,
    ],
    'services' => [
        'factories' => [
            CacheServer::class    => CacheServerFactory::class,
            CacheIOHandler::class => CacheIOHandlerFactory::class,
            CacheClient::class    => CacheClientFactory::class,
            Maintainer::class     => MaintainerFactory::class,
            Bucket::class         => BucketFactory::class,
            CacheEventListener::class => CacheEventListenerFactory::class,
            CacheDataLogger::class => CacheDataLoggerFactory::class
        ],
        'invokables' => [
            ActionHandler::class => ActionHandler::class,
        ],
    ],
];
