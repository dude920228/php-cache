<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

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
        while ($this->running) {
            $connection = @stream_socket_accept($this->socket);
            if ($connection) {
                try {
                    $dataString = $this->ioHandler->readFromSocket($connection);

                    $data = unserialize($dataString);
                    var_dump($data);
                    if ($data['action'] == 'set') {
                        $message = $data['message'];
                        
                        $this->bucket->store(unserialize($message));
                        fwrite($connection, serialize(self::ACK));
                    } else {
                        $key = $data['key'];
                        $package = new Package($key, $this->bucket->get($key));
                        var_dump($package);
                        fwrite($connection, serialize($package));
                    }
                } catch (Exception $ex) {
                    fwrite($connection, self::NACK);
                }
            }
            fclose($connection);
        }
    }

    public function close()
    {
        $this->running = false;
        socket_close($this->socket);
    }

}
