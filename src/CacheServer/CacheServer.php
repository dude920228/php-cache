<?php

namespace PhpCache\CacheServer;

use Exception;
use PhpCache\IO\CacheIOHandler;
use PhpCache\Storage\Bucket;
use PhpCache\Storage\Maintainer;

/**
 * Description of CacheServer
 *
 * @author dude920228
 */
class CacheServer implements CacheServerInterface
{

    private $socket;
    private $running;

    /**
     *
     * @var CacheIOHandler
     */
    private $ioHandler;

    /**
     *
     * @var Bucket
     */
    private $bucket;

    /**
     *
     * @var ActionHandler
     */
    private $actionHandler;

    /**
     *
     * @var Maintainer
     */
    private $maintainer;
    
    private $clients;

    const ACK = 1;
    const NACK = 0;

    public function __construct(
    $ioHandler, $bucket, $actionHandler, $maintainer
    )
    {
        $this->running = true;
        $this->ioHandler = $ioHandler;
        $this->bucket = $bucket;
        $this->actionHandler = $actionHandler;
        $this->maintainer = $maintainer;
        $this->clients = array();
    }

    public function run()
    {
        $this->socket = $this->ioHandler->createServerSocket();
        while ($this->running) {
            $this->maintainer->maintainBucket($this->bucket);
            if (($connection = @socket_accept($this->socket))) {
                $clientId = uniqid();
                $this->clients[$clientId] = $connection;
                socket_set_nonblock($connection);
                $read = $this->clients;
                $write = array();
                $except = array();
                socket_select($read, $write, $except, null);
                $this->maintainer->checkBackup(time(), $this->bucket);
                $dataString = $this->ioHandler->readFromSocket($connection);
                $data = unserialize($dataString);
                ($this->actionHandler)($data, $this->bucket, $this->ioHandler, $connection);
                $this->ioHandler->closeSocket($connection);
                unset($this->clients[$clientId]);
            }
        }
        $this->close();
    }

    public function close()
    {
        $this->ioHandler->closeSocket($this->socket);
    }

}
