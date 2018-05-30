<?php
namespace PhpCache\CacheServer;

/**
 *
 * @author kdudas
 */
interface CacheServerInterface
{
    public function run();
    public function close();
}
