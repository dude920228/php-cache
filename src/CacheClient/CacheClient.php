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

    public function __construct(CacheIOHandler $ioHandler)
    {
        $this->ioHandler = $ioHandler;
    }

    public function set(string $key, $package): void
    {
        $socket = $this->ioHandler->createClientSocket();
        $data = ['action' => 'set', 'key' => $key, 'message' => $package];
        $dataString = serialize($data);
        $this->ioHandler->writeToSocket($socket, $dataString);
        $this->ioHandler->closeSocket($socket);
    }

    public function get(string $key)
    {
        $data = ['action' => 'get', 'key' => $key];
        $socket = $this->ioHandler->createClientSocket();
        $dataString = serialize($data);
        $this->ioHandler->writeToSocket($socket, $dataString);
        $recv = $this->ioHandler->readFromSocket($socket);
        $this->ioHandler->closeSocket($socket);

        return unserialize($recv);
    }

    public function delete(string $key): void
    {
        $data = ['action' => 'delete', 'key' => $key];
        $socket = $this->ioHandler->createClientSocket();
        $dataString = serialize($data);
        $this->ioHandler->writeToSocket($socket, $dataString);
        $this->ioHandler->closeSocket($socket);
    }

    public function quitServer(): void
    {
        $data = ['action' => 'quit'];
        $socket = $this->ioHandler->createClientSocket();
        $dataString = serialize($data);
        $this->ioHandler->writeToSocket($socket, $dataString);
    }

    public function getEntries(): array
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
