<?php
namespace PhpCache\IO;

use PhpCache\IO\Exception\IOException;

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
    
    public function __construct($serverIp, $serverPort, $bufferLength)
    {
        $this->serverIp = $serverIp;
        $this->serverPort = $serverPort;
        $this->bufferLength = $bufferLength;
    }
    
    public function push($package)
    {
        $socket = stream_socket_client($this->serverIp.':'.$this->serverPort, $errno, $errstr, 2);
        $data = ['action' => 'set', 'message' => $package]; 
        stream_socket_sendto($socket, serialize($data));
        $response = $this->readFromSocket($socket);
        fclose($socket);
        if($response < 1) {
            return false;
        }
        return true;
    }
    
    public function fetch($key)
    {
        $socket = stream_socket_client($this->serverIp.':'.$this->serverPort, $errno, $errstr, 2);
        $data = ['action' => 'get', 'key' => $key];
        stream_socket_sendto($socket, serialize($data));
        $recv = stream_get_contents($socket, $this->bufferLength);
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
        if(!is_resource($socket) || (get_resource_type($socket) != 'stream')) {
            throw new IOException("The Socket provided is not a valid socket or stream resource!");
        }
        $recv = "";
        while(!feof($socket)) {
            $recv .= fread($socket, $this->bufferLength);
        }
        fclose($socket);
        return $recv;
    }
}
