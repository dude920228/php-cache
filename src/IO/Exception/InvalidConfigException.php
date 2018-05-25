<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\IO\Exception;

use Exception;

/**
 * Description of newPHPClass
 *
 * @author kdudas
 */
class InvalidConfigException extends Exception
{
    public static function createForMissingIPOrPort()
    {
        throw new InvalidConfigException("Missing ip or port in configuration");
    }
}
