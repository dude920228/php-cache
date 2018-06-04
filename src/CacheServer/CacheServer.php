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
    const ACK = 1;
    const NACK = 0;

    public function __construct(
        $ioHandler,
        $bucket,
        $actionHandler,
        $maintainer
    ) {
        $this->running = true;
        $this->ioHandler = $ioHandler;
        $this->bucket = $bucket;
        $this->actionHandler = $actionHandler;
        $this->maintainer = $maintainer;
    }

    public function run()
    {
        $this->socket = $this->ioHandler->createServerSocket();
        while ($this->running) {
            
            while ($connection = @socket_accept($this->socket)) {
                socket_set_nonblock($connection);
                $this->maintainer->maintainBucket($this->bucket);
                $this->maintainer->checkBackup(time(), $this->bucket);
                try {
                    $dataString = $this->ioHandler->readFromSocket($connection);
                    $data = unserialize($dataString);
                    ($this->actionHandler)($data, $this->bucket, $this->ioHandler, $connection);
                    
                } catch (Exception $ex) {
                    $this->ioHandler->writeToSocket($connection, self::NACK);
                    $this->ioHandler->closeSocket($connection);
                }
            }
        }
    }
    
    public function close()
    {
        $this->running = false;
        $this->ioHandler->closeSocket($this->socket);
    }
}
