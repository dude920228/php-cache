<?php

namespace PhpCache\Storage;

/**
 * Description of MessageBucket
 *
 * @author dude920228
 */
class Bucket implements StorageInterface
{
    private $entries;
    
    public function __construct()
    {
        $this->entries = array();
    }
    
    public function get($key)
    {
        if(!array_key_exists($key, $this->entries)) {
            return false;
        }
        return gzuncompress($this->entries[$key]['content']);
    }

    public function store($key, $entry, $time = null)
    {
        $compressed = gzcompress($entry, 9);
        $this->entries[$key]['content'] = $compressed;
        $this->entries[$key]['created_time'] = is_null($time) ? time() : $time;
        if(! $compressed) {
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
        if(array_key_exists($key, $this->entries)) {
            unset($this->entries[$key]);
            return true;
        }
        return false;
    }
}
