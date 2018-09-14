<?php

namespace PhpCache\CacheServer;

use PhpCache\CacheEventListener\CacheEventListenerInterface;
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
        $eventListener = false;
        if($container->has(CacheEventListenerInterface::class)) {
            $eventListener = $container->get(CacheEventListenerInterface::class);
        }
        return new CacheServer($ioHandler, $bucket, $actionHandler, $maintainer, $eventListener);
    }
}
