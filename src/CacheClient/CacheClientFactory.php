<?php

namespace PhpCache\CacheClient;

use PhpCache\IO\CacheIOHandler;
use Psr\Container\ContainerInterface;

/**
 * Description of CacheClientFactory
 *
 * @author dude920228
 */
class CacheClientFactory
{
    public function __invoke(ContainerInterface $contaier)
    {
        $ioHandler = $contaier->get(CacheIOHandler::class);
        return new CacheClient($ioHandler);
    }
}
