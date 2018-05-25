<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\CacheClient;

use PhpCache\IO\CacheIOHandler;
use PhpCache\Package\Package;


/**
 * Description of newPHPClass
 *
 * @author kdudas
 */
class CacheClient implements ClientInterface
{
    /**
     *
     * @var CacheIOHandler
     */
    private $ioHandler;
    
    public function __construct($ioHandler)
    {
        $this->ioHandler = $ioHandler;
    }
    
    public function sendPackage(Package $package)
    {
        $this->ioHandler->push('set', $package);
    }
    
    public function getPackage($key)
    {
        return $this->ioHandler->fetch($key);
    }

}
