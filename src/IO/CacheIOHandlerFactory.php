<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\IO;

use Psr\Container\ContainerInterface;

/**
 * Description of CacheIOHandlerFactory
 *
 * @author kdudas
 */
class CacheIOHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->getConfig();
        $ioHandler = new CacheIOHandler($config['ip'], $config['port'], $config['bufferSize']);
        return $ioHandler;
    }
}
