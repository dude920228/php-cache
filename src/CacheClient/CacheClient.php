<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\CacheClient;

use PhpCache\Message\Package;

/**
 * Description of newPHPClass
 *
 * @author kdudas
 */
class CacheClient implements ClientInterface
{
    private $serverAddress;
    private $serverPort;
    
    public function __construct($serverAddress, $serverPort)
    {
        $this->serverAddress = $serverAddress;
        $this->serverPort = $serverPort;
    }
    
    public function sendPackage(Package $package)
    {
        $socket = fsockopen($this->serverAddress, $this->serverPort, $errno, $errstr, 2);
        $data = ['action' => 'set', 'message' => $package]; 
       fwrite($socket, serialize($data));
        $response = $this->getResponse($socket);
        if($response < 1) {
            var_dump($response);
        }
        fclose($socket);
    }
    
    public function getPackage($key)
    {
        $socket = fsockopen($this->serverAddress, $this->serverPort, $errno, $errstr, 2);
        $data = ['action' => 'get', 'key' => $key];
        $recv = $this->readFromSocket($socket);
        fclose($socket);
        return igbinary_unserialize($recv);
    }
    
    private function readFromSocket($socket)
    {
        $recv = "";
        while(!feof($socket)) {
            $recv .= fread($socket, 1024);
        }
        return $recv;
    }
    
    private function writeToSocket($socket, $data)
    {
        fwrite($socket, igbinary_serialize($data));
    }
            
    
    private function getResponse($socket)
    {
        return $this->readFromSocket($socket);
    }

}
