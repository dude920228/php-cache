<?php

namespace PhpCache\CacheClient;

/**
 * @author dude920228
 */
interface ClientInterface
{
    public function set($key, $message);

    public function get($key);
}
