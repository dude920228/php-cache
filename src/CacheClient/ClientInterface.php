<?php

namespace PhpCache\CacheClient;

use PhpCache\Package\Package;

/**
 *
 * @author kdudas
 */
interface ClientInterface
{
    public function set($key, $message);
    public function get($key);
}
