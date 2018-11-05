<?php

namespace PhpCache\Storage;

use function strlen;

/**
 * Description of Maintainer.
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

    public function __construct(
        int $ttl,
        string $backupDir,
        int $backupTime,
        int $memoryLimit
    ) {
        $this->ttl = $ttl;
        $this->lastBackupRun = time();
        $this->backupDir = $backupDir;
        $this->backupTime = $backupTime;
        $this->memoryLimit = $memoryLimit;
    }

    /**
     * @param Bucket $bucket
     */
    public function maintainBucket(Bucket $bucket): void
    {
        $entries = $bucket->getEntries();
        foreach ($entries as $key => $entry) {
            $entryElapsedTime = time() - $entry['created_time'];
            if ($entryElapsedTime >= $this->ttl) {
                $bucket->delete($key);
            }
        }
    }

    private function checkMemory(Bucket $bucket): int
    {
        $size = 0;
        foreach ($bucket->getEntries() as $entry) {
            $size += strlen($entry['content']);
        }

        return $size;
    }

    public function backup(Bucket $bucket): void
    {
        $this->createBackupDir();
        $this->backupToFile($bucket);
    }

    public function checkBackup(int $time, Bucket $bucket): void
    {
        if ($time - $this->lastBackupRun >= $this->backupTime ||
            $this->checkMemory($bucket) >= $this->memoryLimit) {
            $this->backup($bucket);
            $this->free($bucket);
        }
    }

    private function free(Bucket $bucket): void
    {
        foreach ($bucket->getEntries() as $key => $entry) {
            $bucket->delete($key);
        }
    }

    private function createBackupDir(): void
    {
        if (!file_exists($this->backupDir)) {
            mkdir($this->backupDir);
        }
    }

    private function backupToFile(Bucket $bucket): void
    {
        foreach ($bucket->getEntries() as $key => $entry) {
            file_put_contents($this->backupDir.'/'.$key.'.dat',
                    serialize($entry));
        }
    }
}
