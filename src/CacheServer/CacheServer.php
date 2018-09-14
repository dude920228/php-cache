<?php

namespace PhpCache\CacheServer;

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
        $ioHandler, $bucket, $actionHandler, $maintainer, $cacheEventListener = false
    ) {
        $this->running = true;
        $this->ioHandler = $ioHandler;
        $this->bucket = $bucket;
        $this->actionHandler = $actionHandler;
        $this->maintainer = $maintainer;
        $this->clients = [];
        $this->cacheEventListener = $cacheEventListener;
    }

    public function run()
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
                ($this->actionHandler)($data, $this->bucket, $this->ioHandler, $connection, $this);
                $this->ioHandler->closeSocket($connection);
                unset($this->clients[$clientId]);
            }
        }
    }

    public function beforeServiceStop()
    {
        $this->close();
        $this->maintainer->backup($this->bucket);
    }

    public function close()
    {
        $this->ioHandler->closeSocket($this->socket);
    }
    
    public function getCacheEventListener()
    {
        return $this->cacheEventListener;
    }
}
