<?php

namespace PhpCache\IO;

use Psr\Container\ContainerInterface;

/**
 * Description of CacheIOHandlerFactory
 *
 * @author dude920228
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
