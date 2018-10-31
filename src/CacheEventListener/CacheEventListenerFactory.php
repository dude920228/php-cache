<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\CacheEventListener;

/**
 * Description of CacheEventListenerFactory
 *
 * @author kdudas
 */
class CacheEventListenerFactory
{
    public function __invoke($container)
    {
        $logger = $container->get(\PhpCache\Logger\CacheDataLogger::class);
        
        return new CacheEventListener($logger);
    }
}
