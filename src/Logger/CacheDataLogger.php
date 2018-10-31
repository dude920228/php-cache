<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\Logger;

/**
 * Description of CacheDataLogger
 *
 * @author kdudas
 */
class CacheDataLogger
{
    private $logFilePath;
    private $logFileDir;
    
    public function __construct($logFilePath)
    {
        $this->logFilePath = $logFilePath;
        $pathParts = explode('/', $logFilePath);
        if(count($pathParts) > 1) {
            unset($pathParts[count($pathParts)-1]);
            $this->logFileDir = implode('/', $pathParts);
            if (!file_exists($this->logFileDir)) {
                mkdir($this->logFileDir);
            }
        }
    }
    
    public function log($entry)
    {
        return file_put_contents($this->logFilePath, $entry."\n", FILE_APPEND);
    }
}
