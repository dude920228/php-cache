<?php
namespace PhpCache\Storage;

/**
 *
 * @author dude920228
 */
interface StorageInterface
{
    public function store($key, $message);
    public function get($key);
}
