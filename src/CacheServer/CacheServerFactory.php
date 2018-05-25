<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\CacheServer;

use PhpCache\IO\CacheIOHandler;
use PhpCache\Storage\PackageBucket;
use Psr\Container\ContainerInterface;

/**
 * Description of CacheServerFactory
 *
 * @author kdudas
 */
class CacheServerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $ioHandler = $container->get(CacheIOHandler::class);
        $bucket = $container->get(PackageBucket::class);
        return new CacheServer($ioHandler, $bucket);
    }
}
