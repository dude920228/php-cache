<?php
namespace PhpCache\CacheServer;
/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

/**
 *
 * @author kdudas
 */
interface CacheServerInterface
{
    public function run();
    public function close();
}
