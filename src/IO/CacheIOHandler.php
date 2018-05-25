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
    
    public function push($action, $package)
    {
        $socket = fsockopen($this->serverIp, $this->serverPort, $errno, $errstr, 2);
        $data = ['action' => 'set', 'message' => serialize($package)]; 
        fwrite($socket, serialize($data));
        $response = 1;//$this->getResponse($socket);
        
        if($response < 1) {
            fclose($socket);
            return false;
        }
        fclose($socket);
        return true;
    }
    
    public function fetch($key)
    {
        $socket = fsockopen($this->serverIp, $this->serverPort, $errno, $errstr, 2);
        $data = ['action' => 'get', 'key' => $key];
        fwrite($socket, serialize($data));
        $recv = "";
        while(!feof($socket)) {
            var_dump($recv);
            $recv .= stream_socket_recvfrom($socket, $this->bufferLength);
        }
        fclose($socket);
        return unserialize($recv);
    }
    
    public function getServerIp()
    {
        return $this->serverIp;
    }
    
    public function getServerPort()
    {
        return $this->serverPort;
    }
    
    public function getBufferLength()
    {
        return $this->bufferLength;
    }
    
    public function createServerSocket()
    {
        return stream_socket_server("tcp://{$this->getServerIp()}:"
        . "{$this->getServerPort()}", $errno, $errstr);
    }
    
    public function readFromSocket($socket)
    {
        return stream_get_contents($socket, $this->bufferLength);
    }
    
    private function getResponse($socket)
    {
        return $this->readFromSocket($socket);
    }
}
