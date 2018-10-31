<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\Logger;

use Psr\Container\ContainerInterface;

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
