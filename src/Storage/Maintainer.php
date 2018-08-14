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
    private $memoryLimit;
    
    public function __construct($ttl, $backupDir, $backupTime, $memoryLimit)
    {
        $this->ttl = $ttl;
        $this->lastBackupRun = time();
        $this->backupDir = $backupDir;
        $this->backupTime = $backupTime;
        $this->memoryLimit = $memoryLimit;
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
    
    private function checkMemory($bucket)
    {
        $size = 0;
        foreach($bucket->getEntries() as $entry) {
            $size += strlen($entry['content']);
        }
        return $size;
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
    
    private function memoryBackup($bucket)
    {
        /* @var $bucket Bucket */
        if($this->checkMemory($bucket) >= $this->memoryLimit) {
            $this->backup($bucket);
            foreach($bucket->getEntries() as $key => $entry) {
                $bucket->delete($key);
            }
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
