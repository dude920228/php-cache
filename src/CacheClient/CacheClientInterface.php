<?php

namespace PhpCache\CacheClient;

/**
 * @author dude920228
 */
interface CacheClientInterface
{
    public function set(string $key, $message);

    public function get(string $key);

    public function delete(string $key);
}
