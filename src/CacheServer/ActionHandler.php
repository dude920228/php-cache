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
        CacheServer $server,
        array $data,
        $connection
    ): ?bool {
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
        CacheServer $server,
        array $data,
        $connection
    ):bool {
        $package = $data['message'];
        if ($server->getEventListener()) {
            $package = $server->getEventListener()->onSet($data['key'], $package);
        }
        $success = $server->getBucket()->store($data['key'], $package);

        return $success;
    }

    private function handleGet(
        CacheServer $server,
        array $data,
        $connection
    ): bool {
        $key = $data['key'];
        $package = $server->getBucket()->get($key);
        if (!$package) {
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
        CacheServer $server,
        array $data,
        $connection
    ): bool {
        $key = $data['key'];
        $package = $server->getBucket()->get($key);
        if (!$package) {
            return false;
        }
        if ($server->getEventListener()) {
            $package = $server->getEventListener()->onDelete($key, $package);
        }
        $success = $server->getBucket()->delete($key);

        return $success;
    }

    private function handleGetEntries(
        CacheServer $server,
        array $data,
        $connection
    ): bool {
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
        CacheServer $server,
        array $data,
        $connection
    ): void {
        $server->close();
    }
}
