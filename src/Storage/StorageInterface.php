<?php
namespace PhpCache\Storage;

use PhpCache\Package\PackageInterface;

/**
 *
 * @author kdudas
 */
interface StorageInterface
{
    public function store($key, $message);
    public function get($key);
}
