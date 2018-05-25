<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\Package;

/**
 * Description of Message
 *
 * @author kdudas
 */
class Package implements PackageInterface
{
    private $content;
    private $key;
    
    public function __construct($key, $content)
    {
        $this->content = $content;
        $this->key = $key;
    }

    public function getContent()
    {
        return $this->content;
    }
    
    public function getKey()
    {
        return $this->key;
    }
    
    public function __toString()
    {
        return $this->content;
    }
}
