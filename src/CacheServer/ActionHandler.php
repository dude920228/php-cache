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
        if(! method_exists($this, $functionName)) {
            return false;
        }
        return call_user_func_array([$this, $functionName], [$data, $bucket, $ioHandler, $connection]);
        
    }

    private function handleSet($data, $bucket, $ioHandler, $connection)
    {
        $package = $data['message'];
        $success = $bucket->store($data['key'], $package);
        $ioHandler->closeSocket($connection);
        return $success;
    }

    private function handleGet($data, $bucket, $ioHandler, $connection)
    {
        $key = $data['key'];
        $package = $bucket->get($key);
        if(! $package) {
            return false;
        }
        $dataToSend = serialize($package);
        $ioHandler->writeToSocket($connection, $dataToSend);
        
        $ioHandler->closeSocket($connection);
        return true;
    }

    private function handleDelete($data, $bucket, $ioHandler, $connection)
    {
        $key = $data['key'];
        $success = $bucket->delete($key);
        $ioHandler->closeSocket($connection);
        return $success;
    }

}
