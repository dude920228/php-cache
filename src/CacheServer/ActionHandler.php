<?php

namespace PhpCache\CacheServer;

/**
 * Description of ActionHandler.
 *
 * @author dude920228
 */
class ActionHandler
{
    public function __invoke(
        $data,
        $bucket,
        $ioHandler,
        $connection,
        $eventListener,
        $maintainer,
        $serverSocket
    ) {
        $action = $data['action'];
        $functionName = 'handle'.ucfirst($action);
        if (!method_exists($this, $functionName)) {
            return false;
        }

        return call_user_func_array([$this, $functionName], [
            $data,
            $bucket,
            $ioHandler,
            $connection,
            $eventListener,
            $maintainer,
            $serverSocket,
        ]);
    }

    private function handleSet(
        $data,
        $bucket,
        $ioHandler,
        $connection,
        $eventListener,
        $maintainer,
        $serverSocket
    ) {
        $package = $data['message'];
        if ($eventListener) {
            $package = $eventListener->onSet($data['key'], $package);
        }
        $success = $bucket->store($data['key'], $package);

        return $success;
    }

    private function handleGet(
        $data,
        $bucket,
        $ioHandler,
        $connection,
        $eventListener,
        $maintainer,
        $serverSocket
    ) {
        $key = $data['key'];
        $package = $bucket->get($key);
        if ($package === false) {
            return false;
        }
        if ($eventListener) {
            $package = $eventListener->onGet($key, $package);
        }
        $dataToSend = serialize($package);
        $ioHandler->writeToSocket($connection, $dataToSend);

        return true;
    }

    private function handleDelete(
        $data,
        $bucket,
        $ioHandler,
        $connection,
        $eventListener,
        $maintainer,
        $serverSocket
    ) {
        $key = $data['key'];
        $package = $bucket->get($key);
        if ($package === false) {
            return false;
        }
        if ($eventListener) {
            $package = $eventListener->onDelete($key, $package);
        }
        $success = $bucket->delete($key);

        return $success;
    }

    private function handleGetEntries(
        $data,
        $bucket,
        $ioHandler,
        $connection,
        $eventListener,
        $maintainer,
        $serverSocket
    ) {
        $entries = $bucket->getEntries();
        $entriesFormatted = [];
        foreach ($entries as $key => $value) {
            $entriesFormatted[$key] = gzuncompress($value['content']);
        }
        $dataToSend = serialize($entriesFormatted);
        $ioHandler->writeToSocket($connection, $dataToSend);

        return true;
    }

    private function handleQuit(
        $data,
        $bucket,
        $ioHandler,
        $connection,
        $eventListener,
        $maintainer,
        $serverSocket
    ) {
        $ioHandler->closeSocket($serverSocket);
        $ioHandler->removeSocketFile();
        $maintainer->backup($bucket);
    }
}
