<?php

namespace PhpCache\CacheServer;

/**
 * Description of ActionHandler.
 *
 * @author dude920228
 */
class ActionHandler
{
    public function __invoke($data, $bucket, $ioHandler, $connection, $server)
    {
        $action = $data['action'];
        $functionName = 'handle'.ucfirst($action);
        if (!method_exists($this, $functionName)) {
            return false;
        }

        return call_user_func_array([$this, $functionName], [$data, $bucket, $ioHandler, $connection, $server]);
    }

    private function handleSet($data, $bucket, $ioHandler, $connection, $server)
    {
        $package = $data['message'];
        $eventListener = $server->getCacheEventListener();
        if ($eventListener) {
            $package = $eventListener->onSet($data['key'], $package);
        }
        $success = $bucket->store($data['key'], $package);

        return $success;
    }

    private function handleGet($data, $bucket, $ioHandler, $connection, $server)
    {
        $key = $data['key'];
        $package = $bucket->get($key);
        if ($package === false) {
            return false;
        }
        $eventListener = $server->getCacheEventListener();
        if ($eventListener) {
            $package = $eventListener->onGet($key, $package);
        }
        $dataToSend = serialize($package);
        $ioHandler->writeToSocket($connection, $dataToSend);

        return true;
    }

    private function handleDelete($data, $bucket, $ioHandler, $connection, $server)
    {
        $key = $data['key'];
        $package = $bucket->get($key);
        if ($package === false) {
            return false;
        }
        $eventListener = $server->getCacheEventListener();
        if ($eventListener) {
            $package = $eventListener->onDelete($key, $package);
        }
        $success = $bucket->delete($key);

        return $success;
    }

    private function handleGetEntries($data, $bucket, $ioHandler, $connection)
    {
        $entries = $bucket->getEntries();
        $entriesFormatted = [];
        foreach ($entries as $key => $value) {
            $entriesFormatted[$key] = gzuncompress($value['content']);
        }
        $dataToSend = serialize($entriesFormatted);
        $ioHandler->writeToSocket($connection, $dataToSend);

        return true;
    }

    private function handleQuit($data, $bucket, $ioHandler, $connection, $server)
    {
        $server->beforeServiceStop();
    }
}
