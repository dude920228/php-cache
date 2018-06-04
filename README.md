# php-cache
Cache implementation for php

# Usage:
#### Prequests:
- PHP 5/7
- composer
#### First you have to run:
```
composer install
```
#### Supperted data types:
- String
- Integer/Float/Double
- Array (serialized)
- Objects (serialized)
#### Server side initiation:
```
<?php
include_once 'vendor/autoload.php';

use PhpCache\CacheServer\CacheServer;
use PhpCache\ServiceManager\ServiceManager;

$config = require_once 'config.php';
$serviceManager = new ServiceManager($config);
$server = $serviceManager->get(CacheServer::class);
$server->run();
```
#### Running the server:
```
php testServer.php
```
#### Client side initiation:
```
<?php
include_once('vendor/autoload.php');

use PhpCache\CacheClient\CacheClient;
use PhpCache\ServiceManager\ServiceManager;

$config = require_once 'config.php';
$serviceManager = new ServiceManager($config);
/* @var $client CacheClient */
$client = $serviceManager->get(CacheClient::class);
$client->set('test', 'ASD');
echo $client->get('test');
```
##### You can run the client either from browser or console
```
php testClient.php
```
