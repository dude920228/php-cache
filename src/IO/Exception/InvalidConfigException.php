<?php

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
