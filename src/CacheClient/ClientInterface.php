<?php

namespace PhpCache\CacheClient;

use PhpCache\Package\Package;

/**
 *
 * @author dude920228
 */
interface ClientInterface
{
    public function set($key, $message);
    public function get($key);
}
