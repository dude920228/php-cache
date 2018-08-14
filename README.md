# php-cache
Cache implementation for php

# Usage:
#### Prequests:
- PHP 5/7
- composer
#### Installing via composer:
```
composer require kdudas/php-cache
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
#### OR
Read the guide in `daemon.sh` on how to run a php script as a service
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
#### CLI Commands:
`./phpCache get <key>` gets entries for the specified key. If no key is specified, it returns all entries.  
`./phpCache set <key> <value>` pushes an entry to the cache pool with the given key - value pair.  
`./phpCache delete <key>` deletes the entry with with the given key  
`./phpCache keys` retrieves all keys in the cache pool