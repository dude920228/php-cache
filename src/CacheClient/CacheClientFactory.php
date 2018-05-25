<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\CacheClient;

use PhpCache\IO\CacheIOHandler;
use Psr\Container\ContainerInterface;

/**
 * Description of CacheClientFactory
 *
 * @author kdudas
 */
class CacheClientFactory
{
    public function __invoke(ContainerInterface $contaier)
    {
        $ioHandler = $contaier->get(CacheIOHandler::class);
        return new CacheClient($ioHandler);
    }
}
