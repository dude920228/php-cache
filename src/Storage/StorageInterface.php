<?php
namespace PhpMQ\Storage;

use PhpMQ\Message\MessageInterface;
/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

/**
 *
 * @author kdudas
 */
interface StorageInterface
{
    public function store(MessageInterface $message);
    public function get($key);
}
