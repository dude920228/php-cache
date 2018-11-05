<?php

namespace PhpCache\CacheServer;

use PhpCache\CacheEventListener\CacheEventListenerInterface;
use PhpCache\IO\CacheIOHandler;
use PhpCache\Storage\Bucket;
use PhpCache\Storage\Maintainer;

/**
 * Description of CacheServer.
 *
 * @author dude920228
 */
class CacheServer implements CacheServerInterface
{
    private $socket;
    private $running;

    /**
     * @var CacheIOHandler
     */
    private $ioHandler;

    /**
     * @var Bucket
     */
    private $bucket;

    /**
     * @var ActionHandler
     */
    private $actionHandler;

    /**
     * @var Maintainer
     */
    private $maintainer;

    private $cacheEventListener;

    private $clients;

    public function __construct(
        CacheIOHandler $ioHandler,
        Bucket $bucket,
        ActionHandler $actionHandler,
        Maintainer $maintainer,
        CacheEventListenerInterface $cacheEventListener = null
    ) {
        $this->running = true;
        $this->ioHandler = $ioHandler;
        $this->bucket = $bucket;
        $this->actionHandler = $actionHandler;
        $this->maintainer = $maintainer;
        $this->clients = [];
        $this->cacheEventListener = $cacheEventListener;
    }

    public function run(): void
    {
        $this->socket = $this->ioHandler->createServerSocket();
        while ($this->running) {
            $this->maintainer->checkBackup(time(), $this->bucket);
            $this->maintainer->maintainBucket($this->bucket);
            if (($connection = @socket_accept($this->socket))) {
                $clientId = uniqid();
                socket_set_nonblock($connection);
                $this->clients[$clientId] = $connection;
                $read = $this->clients;
                $write = [];
                $except = [];
                socket_select($read, $write, $except, 10);
                $dataString = $this->ioHandler->readFromSocket($connection);
                $data = unserialize($dataString);
                ($this->actionHandler)(
                    $this,
                    $data,
                    $connection
                );
                $this->ioHandler->closeSocket($connection);
                unset($this->clients[$clientId]);
            }
        }
    }

    public function getBucket(): Bucket
    {
        return $this->bucket;
    }

    public function getIOHandler(): CacheIOHandler
    {
        return $this->ioHandler;
    }

    public function getMaintainer(): Maintainer
    {
        return $this->maintainer;
    }

    public function getSocket()
    {
        return $this->socket;
    }

    public function getEventListener(): CacheEventListenerInterface
    {
        return $this->cacheEventListener;
    }

    public function close(): void
    {
        $this->maintainer->backup($this->bucket);
        $this->running = false;
        $this->ioHandler->closeSocket($this->socket);
        $this->ioHandler->removeSocket();
    }
}
