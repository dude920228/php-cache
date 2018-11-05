<?php

namespace PhpCache\Storage;

/**
 * Description of MessageBucket.
 *
 * @author dude920228
 */
class Bucket implements StorageInterface
{
    private $entries;
    private $backupDir;

    public function __construct(string $backupDir)
    {
        $this->backupDir = $backupDir;
        $this->entries = [];
    }

    public function get(string $key): ?string
    {
        if (!array_key_exists($key, $this->entries) && !$this->existsInBackup($key)) {
            return null;
        }
        if ($this->existsInBackup($key)) {
            $entry = unserialize($this->getFromBackup($key));

            return gzuncompress($entry['content']);
        }

        return gzuncompress($this->entries[$key]['content']);
    }

    private function existsInBackup($key): bool
    {
        if (file_exists($this->backupDir.'/'.$key.'.dat')) {
            return true;
        }

        return false;
    }

    private function getFromBackup($key): string
    {
        $contents = '';
        $handle = fopen($this->backupDir.'/'.$key.'.dat', 'r+');
        if (is_resource($handle)) {
            while (!feof($handle)) {
                $contents .= fread($handle, 32);
            }
        }

        return $contents;
    }

    public function store(string $key, string $entry, $time = null): bool
    {
        $compressed = gzcompress($entry, 9);
        $this->entries[$key]['content'] = $compressed;
        $this->entries[$key]['created_time'] = is_null($time) ? time() : $time;
        if (!$compressed) {
            return false;
        }

        return true;
    }

    public function getEntries(): array
    {
        return $this->entries;
    }

    public function delete($key): bool
    {
        if (array_key_exists($key, $this->entries)) {
            unset($this->entries[$key]);

            return true;
        }

        return false;
    }
}
