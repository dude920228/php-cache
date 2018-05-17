<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpMQ\MQServer;

use PhpMQ\Message\Message;
use PhpMQ\Storage\MessageBucket;

/**
 * Description of MQServer
 *
 * @author kdudas
 */
class MQServer implements MQServerInterface
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

            $connection = stream_socket_accept($this->socket);
            
            try {
                $data = unserialize(fread($connection, $this->bufferLength));
                var_dump($data);
                if($data['action'] == 'set') {
                    $message = $data['message'];
                    $this->bucket->store(new Message($message));
                    
                    fwrite($connection, self::ACK);
                }
                else {
                    $number = $data['quantity'];
                    var_dump($this->bucket->get($number));
                    fwrite($connection, serialize($this->bucket->get($number)));
                }
            } catch (Exception $ex) {
                fwrite($connection, self::NACK);
            }
        }
    }

    public function close()
    {
        $this->running = false;
        socket_close($this->socket);
    }

}
