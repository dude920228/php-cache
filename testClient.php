<?php
include_once('vendor/autoload.php');

use PhpCache\CacheClient\CacheClient;
use PhpCache\ServiceManager\ServiceManager;

$config = require_once 'config.php';
$serviceManager = new ServiceManager($config);
/* @var $client CacheClient */
$client = $serviceManager->get(CacheClient::class);
$foo = new stdClass;
$foo->foo = 'bar';
$bar = new stdClass;
$bar->baz = 'foo';;
$array = [];
for($i = 0; $i < 100; $i++) {
    $array[] = $foo;
    $array[] = $bar;
}
$client->set('test1', serialize($array));
var_dump($client->get('test1'));
