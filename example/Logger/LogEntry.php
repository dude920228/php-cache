<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\Example\Logger;

/**
 * Description of LogEntry
 *
 * @author kdudas
 */
class LogEntry
{
    const SEVERITY_INFO = 'info';
    const SEVERITY_NOTICE = 'notice';
    const SEVERITY_WARNING = 'warning';
    const SEVERITY_CRITICAL = 'critical';
    
    private $date;
    private $severity;
    private $message;
    
    public function __construct($date, $severity, $message)
    {
        $this->date = $date;
        $this->severity = $severity;
        $this->message = $message;
    }
    
    public function getDate()
    {
        return $this->date;
    }
    
    public function getSeverity()
    {
        return $this->severity;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    public function __toString()
    {
        return '['.$this->date->format('Y-m-d H:i:s')."]\t".$this->severity.": ".$this->message;
    }
}
