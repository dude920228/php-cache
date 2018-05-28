<?php
include_once('vendor/autoload.php');

use PhpCache\CacheClient\CacheClient;
use PhpCache\Package\Package;
use PhpCache\ServiceManager\ServiceManager;

$config = require_once 'config.php';
$serviceManager = new ServiceManager($config);
$client = $serviceManager->get(CacheClient::class);
for($i = 0; $i < 10; $i++) {
    $content = (string)$i;
    $client->sendPackage(new Package('test'.$i, $content));
}
var_dump($client->getPackage('test9'));
$client->close();