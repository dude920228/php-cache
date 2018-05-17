<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpMQ\MQClient;

use PhpMQ\Message\Message;

/**
 * Description of newPHPClass
 *
 * @author kdudas
 */
class MQClient implements ClientInterface
{
    private $serverAddress;
    private $serverPort;
    
    public function __construct($serverAddress, $serverPort)
    {
        $this->serverAddress = $serverAddress;
        $this->serverPort = $serverPort;
    }
    
    public function sendMessage(Message $message)
    {
        $socket = fsockopen($this->serverAddress, $this->serverPort, $errno, $errstr, 2);
        $data = ['action' => 'set', 'message' => $message->getContent()];
        fwrite($socket, serialize($data));
        fclose($socket);
    }
    
    public function getMessages($quantity = 1)
    {
        $socket = fsockopen($this->serverAddress, $this->serverPort, $errno, $errstr, 2);
        $data = ['action' => 'get', 'quantity' => $quantity];
        
        fwrite($socket, serialize($data));
        $recv = fread($socket, 1024);
        
        fclose($socket);
        return unserialize($recv);
    }   
}
