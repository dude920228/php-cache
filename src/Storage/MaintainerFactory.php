<?php

namespace PhpCache\Storage;

use Psr\Container\ContainerInterface;

/**
 * Description of MaintainerFactory
 *
 * @author dude920228
 */
class MaintainerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->getConfig();
        $ttl = 3600;
        $backupDir = __DIR__.'/../../.backup';
        $backupTime = 3600;
        if(array_key_exists('ttl', $config)) {
            $ttl = $config['ttl'];
        }
        if(array_key_exists('backupDir', $config)) {
            $backupDir = $config['backupDir'];
        }
        if(array_key_exists('backupTime', $config)) {
            $backupTime = $config['backupTime'];
        }
        return new Maintainer($ttl, $backupDir, $backupTime);
    }
}
