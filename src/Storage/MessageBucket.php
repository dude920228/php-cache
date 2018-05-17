<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpMQ\Storage;

use PhpMQ\Message\MessageInterface;

/**
 * Description of MessageBucket
 *
 * @author kdudas
 */
class MessageBucket implements StorageInterface
{
    private $messages;
    
    public function __construct()
    {
        $this->messages = array();
    }
    
    public function get($quantity = 1)
    {
        $returned = array();
        for($i = 0; $i < $quantity; $i++) {
            $returned[] = array_shift($this->messages);
        }
        return $returned;
    }

    public function store(MessageInterface $message)
    {
        $this->messages[] = $message;
    }

}
