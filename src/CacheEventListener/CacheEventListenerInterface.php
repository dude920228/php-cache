<?php

namespace PhpCache\CacheEventListener;

/**
 * @author dude920228
 */
interface CacheEventListenerInterface
{
    public function onSet($key, $entry);

    public function onGet($key, $entry);

    public function onDelete($key, $entry);
}
