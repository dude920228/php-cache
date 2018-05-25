<?php
namespace PhpCache\Storage;

use PhpCache\Package\PackageInterface;

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

/**
 *
 * @author kdudas
 */
interface StorageInterface
{
    public function store(PackageInterface $message);
    public function get($key);
}
