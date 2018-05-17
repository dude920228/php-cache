<?php
namespace PhpMQ\MQServer;
/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

/**
 *
 * @author kdudas
 */
interface MQServerInterface
{
    public function run($address, $port);
    public function close();
}
