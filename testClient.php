<?php
$key = $argv[1];
$value = $argv[2];
include_once('vendor/autoload.php');

use PhpCache\CacheClient\CacheClient;
use PhpCache\ServiceManager\ServiceManager;

$config = require_once 'config.php';
$serviceManager = new ServiceManager($config);
/* @var $client CacheClient */
$client = $serviceManager->get(CacheClient::class);

$client->set($key, $value);
$returnedVal = $client->get($key);
echo $key.":\t".$returnedVal."\n";