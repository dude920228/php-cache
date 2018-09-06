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

    public function __construct($backupDir)
    {
        $this->backupDir = $backupDir;
        $this->entries = [];
    }

    public function get($key)
    {
        if (!array_key_exists($key, $this->entries) && !$this->existsInBackup($key)) {
            return false;
        }
        if ($this->existsInBackup($key)) {
            $entry = unserialize($this->getFromBackup($key));

            return gzuncompress($entry['content']);
        }

        return gzuncompress($this->entries[$key]['content']);
    }

    private function existsInBackup($key)
    {
        if (file_exists($this->backupDir.'/'.$key.'.dat')) {
            return true;
        }

        return false;
    }

    private function getFromBackup($key)
    {
        $contents = "";
        $handle = fopen($this->backupDir."/".$key.".dat", "r+");
        if(is_resource($handle)) {
            while(!feof($handle)) {
                $contents .= fread($handle, 32);
            }
        }

        return $contents;
    }

    public function store($key, $entry, $time = null)
    {
        $compressed = gzcompress($entry, 9);
        $this->entries[$key]['content'] = $compressed;
        $this->entries[$key]['created_time'] = is_null($time) ? time() : $time;
        if (!$compressed) {
            return false;
        }

        return true;
    }

    public function getEntries()
    {
        return $this->entries;
    }

    public function delete($key)
    {
        if (array_key_exists($key, $this->entries)) {
            unset($this->entries[$key]);

            return true;
        }

        return false;
    }

    public function getKeys()
    {
        return array_keys($this->entries);
    }
}
