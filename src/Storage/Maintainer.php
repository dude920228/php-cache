<?php

namespace PhpCache\Storage;

/**
 * Description of Maintainer
 *
 * @author dude920228
 */
class Maintainer
{
    private $ttl;
    private $lastBackupRun;
    private $backupDir;
    private $backupTime;
    
    public function __construct($ttl, $backupDir, $backupTime)
    {
        $this->ttl = $ttl;
        $this->lastBackupRun = time();
        $this->backupDir = $backupDir;
        $this->backupTime = $backupTime;
    }
    /**
     * 
     * @param Bucket $bucket
     */
    public function maintainBucket($bucket)
    {
        $entries = $bucket->getEntries();
        foreach($entries as $key => $entry) {
            
            $entryElapsedTime = time() - $entry['created_time'];
            if($entryElapsedTime >= $this->ttl) {
                
                $bucket->delete($key);
            }
        }
    }
    /**
     * 
     * @param Bucket $bucket
     */
    public function backup($bucket)
    {
        $this->createBackupDir();
        $this->backupToFile($bucket);
        
    }
    
    public function checkBackup($time, $bucket)
    {
        if($time - $this->lastBackupRun >= $this->backupTime) {
            $this->backup($bucket);
        } 
    }
    
    private function createBackupDir()
    {
        if(! file_exists($this->backupDir)) {
            mkdir($this->backupDir);
        }
    }
    /**
     * 
     * @param Bucket $bucket
     */
    private function backupToFile($bucket)
    {
        foreach($bucket->getEntries() as $key => $entry) {
            file_put_contents($this->backupDir.'/'.$key.'.dat', serialize($entry));
        }
    }
}
