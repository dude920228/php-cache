<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpMQ\Message;

/**
 * Description of MessageUtils
 *
 * @author kdudas
 */
class MessageUtils
{
    public static function createMessageFromData($data)
    {
        return new Message($data);
    }
}
