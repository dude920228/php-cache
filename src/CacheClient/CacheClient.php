<?php

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
    
    public function set($key, $package)
    {
        $socket = $this->ioHandler->createClientSocket();
        $data = ['action' => 'set', 'key' => $key, 'message' => $package];
        $dataString = serialize($data);
        $bytes = $this->ioHandler->writeToSocket($socket, $dataString);
        $this->ioHandler->closeSocket($socket);
    }
    
    public function get($key)
    {
        $data = ['action' => 'get', 'key' => $key];
        $socket = $this->ioHandler->createClientSocket();
        $dataString = serialize($data);
        $this->ioHandler->writeToSocket($socket, $dataString);
        $recv = $this->ioHandler->readFromSocket($socket);
        $this->ioHandler->closeSocket($socket);
        return unserialize($recv);
    }
    
    public function delete($key)
    {
        $data = ['action' => 'delete', 'key' => $key];
        $socket = $this->ioHandler->createClientSocket();
        $dataString = serialize($data);
        $this->ioHandler->writeToSocket($socket, $dataString);
    }
}
