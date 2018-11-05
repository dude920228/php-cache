<?php

namespace PhpCache\IO;

use PhpCache\IO\Exception\IOException;

/**
 * Description of CacheIOHandler.
 *
 * @author dude920228
 */
class CacheIOHandler
{
    const SOCKET_TYPE_IP = 'ip';
    const SOCKET_TYPE_FILE = 'file';

    private $location;
    private $serverPort;
    private $bufferLength;
    private $socketType;

    public function __construct($location, $serverPort, $bufferLength, $socketType)
    {
        $this->location = $location;
        $this->serverPort = $serverPort;
        $this->bufferLength = $bufferLength;
        $this->socketType = $socketType;
    }

    public function writeToSocket($socket, $dataString)
    {
        $bytes = socket_write($socket, $dataString, strlen($dataString));

        return $bytes;
    }

    public function createServerSocket()
    {
        $socketType = AF_INET;
        if ($this->socketType == self::SOCKET_TYPE_FILE) {
            $socketType = AF_UNIX;
        }
        $socket = socket_create($socketType, SOCK_STREAM, 0);
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        $bindResult = socket_bind($socket, $this->location, $this->serverPort);
        if (!$bindResult) {
            $errorCode = socket_last_error($socket);
            $errorMsg = socket_strerror($errorCode);

            throw new IOException(
                sprintf(
                    "Couldn't create server socket on ip: %s, port: %d. Reason: %s",
                    $this->location,
                    $this->serverPort,
                    $errorMsg
                )
            );
        }
        socket_listen($socket);

        return $socket;
    }

    public function createClientSocket()
    {
        $socketType = AF_INET;
        if ($this->socketType == self::SOCKET_TYPE_FILE) {
            $socketType = AF_UNIX;
        }
        $socket = socket_create($socketType, SOCK_STREAM, 0);
        $connectionResult = socket_connect(
            $socket,
            $this->location,
            $this->serverPort
        );
        if (!$connectionResult) {
            $errorCode = socket_last_error($socket);
            $errorMsg = socket_strerror($errorCode);

            throw new IOException(
                sprintf(
                    "Couldn't connect to server socket on ip: %s, port: %d. Reason: %s",
                    $this->location,
                    $this->serverPort,
                    $errorMsg
                )
            );
        }

        return $socket;
    }

    public function readFromSocket($socket)
    {
        $recv = '';
        $buffer = '';
        while (socket_recv($socket, $buffer, $this->bufferLength, MSG_WAITALL)) {
            $recv .= $buffer;
        }

        return $recv;
    }

    public function closeSocket($socket)
    {
        socket_close($socket);
    }

    public function removeSocket()
    {
        if ($this->socketType == self::SOCKET_TYPE_FILE) {
            unlink($this->location);
        }
    }

    public function getServerIp()
    {
        return $this->location;
    }

    public function getServerPort()
    {
        return $this->serverPort;
    }

    public function getBufferLength()
    {
        return $this->bufferLength;
    }
}
