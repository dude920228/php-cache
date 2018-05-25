<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\CacheClient;

use PhpCache\Message\Package;

/**
 *
 * @author kdudas
 */
interface ClientInterface
{
    public function sendPackage(Package $message);
    public function getPackage($key);
}
