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
        $server,
        $data,
        $connection
    ) {
        $action = $data['action'];
        $functionName = 'handle'.ucfirst($action);
        if (!method_exists($this, $functionName)) {
            return false;
        }

        return call_user_func_array([$this, $functionName], [
            $server,
            $data,
            $connection,
        ]);
    }

    private function handleSet(
        $server,
        $data,
        $connection
    ) {
        $package = $data['message'];
        if ($server->getEventListener()) {
            $package = $server->getEventListener()->onSet($data['key'], $package);
        }
        $success = $server->getBucket()->store($data['key'], $package);

        return $success;
    }

    private function handleGet(
        $server,
        $data,
        $connection
    ) {
        $key = $data['key'];
        $package = $server->getBucket()->get($key);
        if ($package === false) {
            return false;
        }
        if ($server->getEventListener()) {
            $package = $server->getEventListener()->onGet($key, $package);
        }
        $dataToSend = serialize($package);
        $server->getIOHandler()->writeToSocket($connection, $dataToSend);

        return true;
    }

    private function handleDelete(
        $server,
        $data,
        $connection
    ) {
        $key = $data['key'];
        $package = $server->getBucket()->get($key);
        if ($package === false) {
            return false;
        }
        if ($server->getEventListener()) {
            $package = $server->getEventListener()->onDelete($key, $package);
        }
        $success = $server->getBucket()->delete($key);

        return $success;
    }

    private function handleGetEntries(
        $server,
        $data,
        $connection
    ) {
        $entries = $server->getBucket()->getEntries();
        $entriesFormatted = [];
        foreach ($entries as $key => $value) {
            $entriesFormatted[$key] = gzuncompress($value['content']);
        }
        $dataToSend = serialize($entriesFormatted);
        $server->getIOHandler()->writeToSocket($connection, $dataToSend);

        return true;
    }

    private function handleQuit(
        $server,
        $data,
        $connection
    ) {
        $server->close();
    }
}
