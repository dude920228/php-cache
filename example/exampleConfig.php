<?php

use PhpCache\CacheEventListener\CacheEventListenerInterface;
use PhpCache\Example\EventListener\CacheEventListener;
use PhpCache\Example\Logger\CacheDataLogger;
use PhpCache\Example\Logger\CacheDataLoggerFactory;

return [
    'config' => [
        'logFilePath' => __DIR__.'/log/example.log',
    ],
    'services' => [
        'aliases' => [
            CacheEventListenerInterface::class => CacheEventListener::class,
        ],
        'factories' => [
            CacheDataLogger::class    => CacheDataLoggerFactory::class,
            CacheEventListener::class => \PhpCache\Example\EventListener\CacheEventListenerFactory::class,
        ],

    ],
];
