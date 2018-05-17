<?php
include_once('vendor/autoload.php');
use PhpMQ\Message\Message;
use PhpMQ\MQClient\MQClient;

$client = new MQClient('127.0.0.1', 9000);
for($i = 0; $i < 10; $i++) {
    $content = (string)rand(0, 9999);
    $client->sendMessage(new Message($content));
    echo $content."\n";
}
var_dump($client->getMessages(5));