<?php

$phar = new Phar('php-cache.phar', 0, 'php-cache.phar');
$phar->startBuffering();
$defaultStub = $phar->createDefaultStub('phpCache');
// Add a directory with the src/, vendor/ and phpCache files in it
$phar->buildFromDirectory(__DIR__.'/phar-build');
$stub = "#!/usr/bin/env php \n".$defaultStub;
$phar->setStub($stub);
$phar->stopBuffering();