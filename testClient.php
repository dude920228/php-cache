<?php
include_once('vendor/autoload.php');

use PhpCache\CacheClient\CacheClient;
use PhpCache\Package\Package;

$client = new CacheClient('127.0.0.1', 9000);
for($i = 0; $i < 10; $i++) {
    $content = (string)rand(0, 9999);
    $client->sendPackage('', new Package($content));
    echo $content."\n";
}
var_dump($client->getPackage());