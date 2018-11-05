<?php

namespace PhpCache\CacheServer;

/**
 * @author dude920228
 */
interface CacheServerInterface
{
    public function run(): void;

    public function close(): void;
}
