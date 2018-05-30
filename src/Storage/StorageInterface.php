<?php
namespace PhpCache\Storage;

/**
 *
 * @author kdudas
 */
interface StorageInterface
{
    public function store($key, $message);
    public function get($key);
}
