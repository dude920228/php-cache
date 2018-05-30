<?php

namespace PhpCache\Package;

/**
 *
 * @author kdudas
 */
interface PackageInterface
{
    public function getKey();
    public function getContent();
}
