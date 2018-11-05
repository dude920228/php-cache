<?php

namespace PhpCache\CacheEventListener;

/**
 * @author dude920228
 */
interface CacheEventListenerInterface
{
    public function onSet(string $key, $entry);

    public function onGet(string $key, $entry);

    public function onDelete(string $key, $entry);
}
