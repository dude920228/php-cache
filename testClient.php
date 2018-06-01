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
$bar->baz = 'foo';
//$client->set('test1', $foo);
//$client->set('test2', $bar);
var_dump($client->get('test1'));