<?php

namespace PhpCache\Example\Logger;

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

use PhpCache\Example\Logger\CacheDataLogger;

/**
 * Description of CacheDataLoggerFactory
 *
 * @author kdudas
 */
class CacheDataLoggerFactory
{
    public function __invoke($container)
    {
        $config = $container->getConfig();
        
        return new CacheDataLogger($config['logFilePath']);
    }
}
