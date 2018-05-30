<?php
namespace PhpCache\Storage;

use PhpCache\Package\PackageInterface;

/**
 *
 * @author kdudas
 */
interface StorageInterface
{
    public function store(PackageInterface $message);
    public function get($key);
}
