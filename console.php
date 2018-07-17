#!/usr/bin/env php
<?php

use PhpCache\Commands\DeleteCommand;
use PhpCache\Commands\GetCommand;
use PhpCache\Commands\SetCommand;
use Symfony\Component\Console\Application;

/* 
 * All rights reserved © 2018 Legow Hosting Kft.
 */
require_once 'vendor/autoload.php';
$config = require_once 'config.php';
$app = new Application();
$app->add(new GetCommand($config));
$app->add(new SetCommand($config));
$app->add(new DeleteCommand($config));
$app->run();