[![StyleCI](https://github.styleci.io/repos/135454839/shield?branch=master)](https://github.styleci.io/repos/135454839) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dude920228/php-cache/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/dude920228/php-cache/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/dude920228/php-cache/badges/build.png?b=master)](https://scrutinizer-ci.com/g/dude920228/php-cache/build-status/master)
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
#### Creating a new server instance
```
<?php

ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php-cache.log');
require_once 'vendor/autoload.php';

use PhpCache\CacheServer\CacheServer;
use PhpCache\ServiceManager\ConfigAggregator;
use PhpCache\ServiceManager\ServiceManager;
// You can import multiple config files to overwrite parameters in the basic config or add extra factories
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
# in this case you have to make the phar file globally executable because the daemon file will run it before stopping the service  
# create a directory called 'phar-build' -> copy the vendor and src folders to it -> copy the phpCache file to the directory -> run pharBuilder.php.
mv php-cache.phar /usr/local/bin/php-cache
chmod +x /usr/local/bin/php-cache
# now you can use systemctl style service management
sudo service php-cache start
```
##### Note 1: you can modify the contents of `daemon.sh` if you want to use other directories
##### Note 2:  A `.phar` file is a php archive file, which packs a php application into one file. If the sources or the config changes, it needs to be rebuilt! 

#### Adding Server Event Listeners:
- Create a config file that looks like this: 
```
<?php
use PhpCache\CacheServerInter;
use Example\ExampleEventListener;
use Example\ExampleEventListenerFactory;
return [
    'services' => [
        'aliases' => [
            'PhpCache\CacheEventListener\CacheEventListenerInterface::class' => Example\ExampleEventListener::class
        ],
        'factories' => [
            'Example\ExampleEventListener::class' => ExampleEventListenerFactory::class
        ]
    ]
]
```
- Create the event listener class:
```
    <?php
    namespace Example;
    use PhpCache\CacheEventListener\CacheEventListenerInterface;
    
    class ExampleEventListener implements CacheEventListenerInterface
    {
        //implement the abstract methods, add custom constructor with extra services (like db adapter, logger, etc...)
    }
```
- Create the factory for the event listener class:
```
<?php
namespace Example;

class ExampleEventListenerFactory
{
    public function __invoke($container)
    {
        //Get the extra service you want from the container here and add add them to the constructor call
        return new ExampleEventListener();
    }
}
```

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
`./php-cache.phar get <key>` gets entries for the specified key. If no key is specified, it returns all entries.  
`./php-cache.phar set <key> <value>` pushes an entry to the cache pool with the given key - value pair.  
`./php-cache.phar delete <key>` deletes the entry with with the given key  