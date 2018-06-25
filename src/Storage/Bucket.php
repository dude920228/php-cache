<?php

namespace PhpCache\Storage;

use ArrayAccess;

/**
 * Description of MessageBucket
 *
 * @author dude920228
 */
class Bucket implements StorageInterface, ArrayAccess
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
    
    public function getKeys()
    {
        return array_keys($this->entries);
    }
    
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->entries);
    }

    public function offsetGet($offset)
    {
        return $this->entries[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->entries[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->entries[$offset]);
    }

}
