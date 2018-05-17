<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpMQ\Message;

/**
 * Description of Message
 *
 * @author kdudas
 */
class Message implements MessageInterface
{
    private $content;
    
    public function __construct($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }
    
    public function __toString()
    {
        return $this->content;
    }
}
