<?php

namespace PhpCache\IO\Exception;

use Exception;

/**
 * Description of newPHPClass
 *
 * @author dude920228
 */
class InvalidConfigException extends Exception
{
    public static function createForMissingIPOrPort()
    {
        throw new InvalidConfigException("Missing ip or port in configuration");
    }
}
