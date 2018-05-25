<?php
namespace PhpCache\IO;

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

/**
 * Description of CacheIOHandler
 *
 * @author kdudas
 */
class CacheIOHandler
{
    private $serverIp;
    private $serverPort;
    private $bufferLength;
    private $socket;
    
    public function __construct($serverIp, $serverPort, $bufferLength)
    {
        $this->serverIp = $serverIp;
        $this->serverPort = $serverPort;
        $this->bufferLength = $bufferLength;
    }
    
    public function push()
    {
        
    }
    
    public function fetch()
    {
        $message = "";
        while(!feof($this->socket)) {
            $message = fread($this->socket, $this->bufferLength);
        }
        return $message;
    }
}
