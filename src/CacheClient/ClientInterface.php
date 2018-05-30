<?php

namespace PhpCache\CacheClient;

use PhpCache\Package\Package;

/**
 *
 * @author kdudas
 */
interface ClientInterface
{
    public function sendPackage(Package $message);
    public function getPackage($key);
}
