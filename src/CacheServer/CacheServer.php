<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\CacheServer;

use PhpCache\Message\Package;
use PhpCache\Storage\MessageBucket;

/**
 * Description of CacheServer
 *
 * @author kdudas
 */
class CacheServer implements CacheServerInterface
{

    private $socket;
    private $bucket;
    private $running;
    private $bufferLength;

    const ACK = 1;
    const NACK = 0;

    public function __construct($bufferLength = 128)
    {
        $this->bucket = new MessageBucket();
        $this->running = true;
        $this->bufferLength = $bufferLength;
    }

    public function run($address, $port)
    {
        $this->socket = stream_socket_server("tcp://{$address}:{$port}", $errno, $errstr);
        while ($this->running) {

            $connection = @stream_socket_accept($this->socket);
            if ($connection) {
                try {
                    $data = fread($connection, $this->bufferLength);
                    if ($data['action'] == 'set') {
                        $message = $data['message'];
                        $this->bucket->store(new Package($message));

                        fwrite($connection, self::ACK);
                    } else {
                        $number = $data['quantity'];
                        fwrite($connection, $this->bucket->get($number));
                    }
                } catch (Exception $ex) {
                    fwrite($connection, self::NACK);
                    fclose($connection);
                }
                fclose($connection);
            }
        }
    }

    public function close()
    {
        $this->running = false;
        socket_close($this->socket);
    }

}
