<?php

namespace PhpCache\CacheServer;

use PhpCache\IO\CacheIOHandler;
use PhpCache\Storage\Bucket;
use PhpCache\Storage\Maintainer;
use Psr\Container\ContainerInterface;

/**
 * Description of CacheServerFactory.
 *
 * @author dude920228
 */
class CacheServerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $ioHandler = $container->get(CacheIOHandler::class);
        /* @var $bucket Bucket */
        $bucket = $container->get(Bucket::class);
        $actionHandler = $container->get(ActionHandler::class);
        $maintainer = $container->get(Maintainer::class);

        return new CacheServer($ioHandler, $bucket, $actionHandler, $maintainer);
    }
}
