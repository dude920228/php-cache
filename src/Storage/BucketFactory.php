<?php


namespace PhpCache\Storage;

/**
 * Description of BucketFactory
 *
 * @author dude920228
 */
class BucketFactory
{
    public function __invoke(\Psr\Container\ContainerInterface $container)
    {
        $bucket = new Bucket();
        $config = $container->getConfig();
        $backupDir = $config['backupDir'];
        if(! file_exists($backupDir)) {
            return $bucket;
        }
        return $this->restoreFromBackup($bucket, $backupDir);
    }
    
    private function restoreFromBackup($bucket, $backupDir)
    {
        foreach(new \DirectoryIterator($backupDir) as $file) {
            if(! $file->isDot() && $file->isFile()) {
                $keyParts = explode('.', $file->getFilename());
                $key = $keyParts[0];
                $handle = $file->openFile("r");
                $contents = "";
                while(! $handle->eof()) {
                    $contents .= $handle->fread(128);
                }
                if($contents != "") {
                    $unserialized = unserialize($contents);
                    $unserialized['content'] = gzuncompress($unserialized['content']);
                    $bucket->store($key, $unserialized['content'], $unserialized['created_time']);
                }
                unlink($file->getPathname());
            }
        }
        return $bucket;
    }
}
