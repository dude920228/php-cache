<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\Storage;

use Psr\Container\ContainerInterface;

/**
 * Description of MaintainerFactory
 *
 * @author kdudas
 */
class MaintainerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->getConfig();
        $ttl = 3600;
        if(array_key_exists('ttl', $config)) {
            $ttl = $config['ttl'];
        }
        return new Maintainer($ttl);
    }
}
