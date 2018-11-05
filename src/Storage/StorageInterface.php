<?php

namespace PhpCache\Storage;

/**
 * @author dude920228
 */
interface StorageInterface
{
    public function store(string $key, string $message);

    public function get(string $key);
}
