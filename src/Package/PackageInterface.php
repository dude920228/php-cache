<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

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
