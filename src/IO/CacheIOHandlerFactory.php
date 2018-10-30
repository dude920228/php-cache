<?php

namespace PhpCache\IO;

use Psr\Container\ContainerInterface;

/**
 * Description of CacheIOHandlerFactory.
 *
 * @author dude920228
 */
class CacheIOHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->getConfig();
        $ioHandler = new CacheIOHandler($config['location'], $config['port'], $config['bufferSize'], $config['socketType']);

        return $ioHandler;
    }
}
