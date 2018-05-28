<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

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
    private $socket;
    
    public function __construct($ioHandler)
    {
        $this->ioHandler = $ioHandler;
        $this->socket = fsockopen($this->ioHandler->getServerIp(), $this->ioHandler->getServerPort());
    }
    
    public function sendPackage(Package $package)
    {
        $data = ['action' => 'set', 'message' => $package];
        fwrite($this->socket, serialize($data));
        fflush($this->socket);
    }
    
    public function getPackage($key)
    {
        $data = ['action' => 'get', 'key' => $key];
        fwrite($this->socket, serialize($data));
        fflush($this->socket);
        $recv = "";
        while (!feof($this->socket)) {
            $recv .= fread($this->socket, $this->ioHandler->getBufferLength());
        }
        return unserialize($recv);
    }
    
    public function close()
    {
        return fclose($this->socket);
    }
}
