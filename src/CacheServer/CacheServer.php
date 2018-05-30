<?php

namespace PhpCache\CacheServer;

use Exception;
use PhpCache\IO\CacheIOHandler;
use PhpCache\Package\Package;
use PhpCache\Storage\PackageBucket;

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
     * @var PackageBucket
     */
    private $bucket;

    const ACK = 1;
    const NACK = 0;

    public function __construct($ioHandler, $bucket)
    {
        $this->running = true;
        $this->ioHandler = $ioHandler;
        $this->bucket = $bucket;
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
                    if ($data['action'] == 'set') {
                        $package = $data['message'];
                        $this->bucket->store($package);
                        $this->ioHandler->closeSocket($connection);
                    } else {
                        $key = $data['key'];
                        $package = $this->bucket->get($key);
                        $dataToSend = serialize($package);
                        $this->ioHandler->writeToSocket($connection, $dataToSend);
                        $this->ioHandler->closeSocket($connection);
                    }
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
