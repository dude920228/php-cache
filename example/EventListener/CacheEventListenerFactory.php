<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\Example\EventListener;

use PhpCache\Example\EventListener\CacheEventListener;
use PhpCache\Example\Logger\CacheDataLogger;


/**
 * Description of CacheEventListenerFactory
 *
 * @author kdudas
 */
class CacheEventListenerFactory
{
    public function __invoke($container)
    {
        $logger = $container->get(CacheDataLogger::class);
        
        return new CacheEventListener($logger);
    }
}
