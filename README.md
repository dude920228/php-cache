[![StyleCI](https://github.styleci.io/repos/135454839/shield?branch=master)](https://github.styleci.io/repos/135454839) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dude920228/php-cache/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/dude920228/php-cache/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/dude920228/php-cache/badges/build.png?b=master)](https://scrutinizer-ci.com/g/dude920228/php-cache/build-status/master)
# php-cache
Cache implementation for php

# Usage:
#### Prequests:
- PHP 7.2
- composer
#### Installing via composer:
```
composer require kdudas/php-cache
```
#### Supperted data types:
- String
- Integer/Float/Double
- Array
- Objects
#### Creating a new server instance
```
<?php

ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php-cache.log');
require_once 'vendor/autoload.php';

use PhpCache\CacheServer\CacheServer;
use PhpCache\ServiceManager\ConfigAggregator;
use PhpCache\ServiceManager\ServiceManager;
// You can import multiple config files to overwrite parameters in the basic config or add extra parameters, including dependency injection
$config = include_once 'config.php';
$configAggregator = new ConfigAggregator();
$configAggregator->addConfig($config);
$serviceManager = new ServiceManager($configAggregator->getMergedConfig());
$server = $serviceManager->get(CacheServer::class);
$server->run();
```
#### Running the server:
```
php testServer.php
```
#### OR
```
mv daemon.sh /etc/init.d/php-cache
chmod +x /etc/init.d/php-cache
# now you can use systemctl style service management
sudo service php-cache start
```
##### Note: you can modify the contents of `daemon.sh` if you want to use other directories
#### Configuration array:
- `config`: Basic configuration array  
-- `memoryLimit`: as the name suggests, after we exceed the limit, our data in the cache pool gets backed up to file system  
-- `location`: server IP address or socket file location (string)  
-- `port`: the port to run the sockets on (number)  
-- `bufferSize`: how big chunks of data is being read from a stream (bytes)  
-- `ttl`: time to live; how long an entry should take space up in the cache pool before being deleted (seconds)  
-- `backupTime`: schedule backups (seconds)  
-- `backupDir`: where to store backed up data? A backup is made when we are shutting down the server service, when the scheduled backup occures or our cache pool exceeded it's memory limit  
-- `socketType`: which socket type should we use? Open a port on the network for the socket or create a file for the socket. Values must be either `file` (`CacheIOHandler::SOCKET_TYPE_FILE`) or `ip` (`CacheIOHandler::SOCKET_TYPE_IP`)  
- `services`: service manager configuration  
-- `aliases`: a name assigned for a real service (Example: `'cache-server' => CacheServer::class`)  
-- `factories`: service name with factory name for service pairs  
-- `invokables`: services with no dependencies  

#### Creating a new client instance
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
`./phpCache delete <key>` deletes the entry with the given key