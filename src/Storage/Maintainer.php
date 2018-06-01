<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\Storage;

/**
 * Description of Maintainer
 *
 * @author kdudas
 */
class Maintainer
{
    private $ttl;
    private $lastBackupRun;
    
    public function __construct($ttl)
    {
        $this->ttl = $ttl;
        $this->lastBackupRun = null;
    }
    
    public function maintainBucket(Bucket $bucket)
    {
        $entries = $bucket->getEntries();
        foreach($entries as $key => $entry) {
            $now = time();
            $entryElapsedTime = $now - $entry['created_time'];
            if($entryElapsedTime >= $this->ttl) {
                
                $bucket->delete($key);
            }
        }
    }
    
    public function backup()
    {
        
    }
}
