<?php

namespace PhpCache\CacheServer;

use Exception;
use PhpCache\IO\CacheIOHandler;
use PhpCache\Package\Package;
use PhpCache\Storage\Bucket;

/**
 * Description of CacheServer
 *
 * @author kdudas
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
    
    const ACK = 1;
    const NACK = 0;

    public function __construct($ioHandler, $bucket, $actionHandler)
    {
        $this->running = true;
        $this->ioHandler = $ioHandler;
        $this->bucket = $bucket;
        $this->actionHandler = $actionHandler;
    }

    public function run()
    {
        $this->socket = $this->ioHandler->createServerSocket();
        while (true) {
            while ($connection = @socket_accept($this->socket)) {
                socket_set_nonblock($connection);
                try {
                    $dataString = $this->ioHandler->readFromSocket($connection);
                    $data = unserialize($dataString);
                    ($this->actionHandler)($data, $this->bucket, $this->ioHandler, $connection);
                    
                } catch (Exception $ex) {
                    fwrite($connection, self::NACK);
                    fflush($connection);
                    fclose($connection);
                }
            }
        }
    }
    
    public function close()
    {
        $this->ioHandler->closeSocket($this->socket);
    }
}
