<?php

namespace PhpCache\Example\Logger;

/**
 * Description of CacheDataLoggerFactory.
 *
 * @author kdudas
 */
class CacheDataLoggerFactory
{
    public function __invoke($container)
    {
        $config = $container->getConfig();

        return new CacheDataLogger($config['logFilePath']);
    }
}
