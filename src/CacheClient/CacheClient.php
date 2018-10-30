<?php

namespace PhpCache\CacheClient;

use PhpCache\IO\CacheIOHandler;

/**
 * Description of newPHPClass.
 *
 * @author dude920228
 */
class CacheClient implements CacheClientInterface
{
    /**
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
        $this->ioHandler->writeToSocket($socket, $dataString);
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
        $this->ioHandler->closeSocket($socket);
    }

    public function quitServer()
    {
        $data = ['action' => 'quit'];
        $socket = $this->ioHandler->createClientSocket();
        $dataString = serialize($data);
        $this->ioHandler->writeToSocket($socket, $dataString);
    }

    public function getEntries()
    {
        $data = ['action' => 'getEntries'];
        $socket = $this->ioHandler->createClientSocket();
        $dataString = serialize($data);
        $this->ioHandler->writeToSocket($socket, $dataString);
        $entries = $this->ioHandler->readFromSocket($socket);
        $this->ioHandler->closeSocket($socket);

        return unserialize($entries);
    }
}
