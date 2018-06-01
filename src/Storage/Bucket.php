<?php

namespace PhpCache\Storage;

/**
 * Description of MessageBucket
 *
 * @author kdudas
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
        return $this->entries[$key]['content'];
    }

    public function store($key, $message)
    {
        $this->entries[$key]['content'] = $message;
        $this->entries[$key]['created_time'] = time();
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
