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
    private $serverIp;
    private $serverPort;
    private $bufferLength;

    public function __construct($serverIp, $serverPort, $bufferLength)
    {
        $this->serverIp = $serverIp;
        $this->serverPort = $serverPort;
        $this->bufferLength = $bufferLength;
    }

    public function writeToSocket($socket, $dataString)
    {
        $bytes = socket_write($socket, $dataString, strlen($dataString));

        return $bytes;
    }

    public function createServerSocket()
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        $bindResult = socket_bind($socket, $this->serverIp, $this->serverPort);
        if (!$bindResult) {
            $errorCode = socket_last_error($socket);
            $errorMsg = socket_strerror($errorCode);

            throw new IOException(
                sprintf(
                    "Couldn't create server socket on ip: %s, port: %d. Reason: %s",
                    $this->serverIp,
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
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $connectionResult = socket_connect(
            $socket,
            $this->serverIp,
            $this->serverPort
        );
        if (!$connectionResult) {
            $errorCode = socket_last_error($socket);
            $errorMsg = socket_strerror($errorCode);

            throw new IOException(
                sprintf(
                    "Couldn't connect to server socket on ip: %s, port: %d. Reason: %s",
                    $this->serverIp,
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
        return socket_close($socket);
    }

    public function getServerIp()
    {
        return $this->serverIp;
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
