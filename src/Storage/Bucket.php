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
            return null;
        }
        return gzuncompress($this->entries[$key]['content']);
    }

    public function store($key, $entry, $time = null)
    {
        $this->entries[$key]['content'] = gzcompress($entry, 9);
        $this->entries[$key]['created_time'] = is_null($time) ? time() : $time;
    }
    
    public function getEntries()
    {
        return $this->entries;
    }
    
    public function delete($key)
    {
        if(array_key_exists($key, $this->entries)) {
            unset($this->entries[$key]);
        }
    }
}
