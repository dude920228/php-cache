<?php

namespace PhpCache\CacheServer;

use ReflectionMethod;

/**
 * Description of ActionHandler
 *
 * @author dude920228
 */
class ActionHandler
{

    public function __invoke($data, $bucket, $ioHandler, $connection)
    {
        $action = $data['action'];
        $functionName = 'handle'.ucfirst($action);
        call_user_func_array([$this, $functionName], [$data, $bucket, $ioHandler, $connection]);
    }

    private function handleSet($data, $bucket, $ioHandler, $connection)
    {
        $package = $data['message'];
        $bucket->store($data['key'], $package);
        $ioHandler->closeSocket($connection);
    }

    private function handleGet($data, $bucket, $ioHandler, $connection)
    {
        $key = $data['key'];
        $package = $bucket->get($key);
        $dataToSend = serialize($package);
        $ioHandler->writeToSocket($connection, $dataToSend);
        
        $ioHandler->closeSocket($connection);
    }

    private function handleDelete($data, $bucket, $ioHandler, $connection)
    {
        $key = $data['key'];
        $bucket->delete($key);
        $ioHandler->closeSocket($connection);
    }

}
