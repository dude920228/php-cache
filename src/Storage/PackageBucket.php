<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\Storage;

use PhpCache\Message\PackageInterface;

/**
 * Description of MessageBucket
 *
 * @author kdudas
 */
class PackageBucket implements StorageInterface
{
    private $packages;
    
    public function __construct()
    {
        $this->packages = array();
    }
    
    public function get($key)
    {
        return $this->packages[$key];
    }

    public function store(PackageInterface $message)
    {
        $this->packages[] = $message;
    }

}
