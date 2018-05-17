<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpMQ\MQClient;

use PhpMQ\Message\Message;

/**
 *
 * @author kdudas
 */
interface ClientInterface
{
    public function sendMessage(Message $message);
}
