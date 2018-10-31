<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\Example\EventListener;

use DateTimeImmutable;
use DateTimeZone;
use PhpCache\CacheEventListener\CacheEventListenerInterface;
use PhpCache\Example\Logger\LogEntry;

/**
 * Description of CacheEventListener
 *
 * @author kdudas
 */
class CacheEventListener implements CacheEventListenerInterface
{
    private $logger;
    
    public function __construct($logger)
    {
        $this->logger = $logger;
    }
    
    public function onDelete($key, $entry)
    {
        $logEntry = new LogEntry(
            new DateTimeImmutable(date('Y-m-d H:i:s'), new DateTimeZone('UTC')),
            LogEntry::SEVERITY_INFO,
            'Cache Entry with key '.$key.' deleted'
        );
        
        $this->logger->log($logEntry);
        
        return $entry;
    }

    public function onGet($key, $entry)
    {
        $logEntry = new LogEntry(
            new DateTimeImmutable(date('Y-m-d H:i:s'), new DateTimeZone('UTC')),
            LogEntry::SEVERITY_INFO,
            'Cache Entry with key '.$key.' requested'
        );
        
        $this->logger->log($logEntry);
        
        return $entry;
    }

    public function onSet($key, $entry)
    {
        $logEntry = new LogEntry(
            new DateTimeImmutable(date('Y-m-d H:i:s'), new DateTimeZone('UTC')),
            LogEntry::SEVERITY_INFO,
            'Cache Entry with key '.$key.' was set into cache pool'
        );
        
        $this->logger->log($logEntry);
        
        return $entry;
    }

}
