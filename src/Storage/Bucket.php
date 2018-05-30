<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\Storage;

use PhpCache\Package\PackageInterface;

/**
 * Description of MessageBucket
 *
 * @author kdudas
 */
class Bucket implements StorageInterface
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

    public function store($key, $message)
    {
        $this->packages[$key] = $message;
    }
    
    public function delete($key)
    {
        if(isset($this->packages[$key])) {
            unset($this->packages[$key]);
        }
    }
}
