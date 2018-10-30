<?php

namespace PhpCache\CacheClient;

/**
 * @author dude920228
 */
interface CacheClientInterface
{
    public function set($key, $message);

    public function get($key);
    
    public function delete($key);
}
