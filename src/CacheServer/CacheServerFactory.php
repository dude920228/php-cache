<?php

namespace PhpCache\CacheServer;

use PhpCache\IO\CacheIOHandler;
use PhpCache\Storage\Bucket;
use PhpCache\Storage\Maintainer;
use Psr\Container\ContainerInterface;

/**
 * Description of CacheServerFactory
 *
 * @author dude920228
 */
class CacheServerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $ioHandler = $container->get(CacheIOHandler::class);
        /* @var $bucket Bucket */
        $bucket = $container->get(Bucket::class);
        $config = $container->getConfig();
        $backupDir = $config['backupDir'];
        $restoredBucket = $this->restoreFromBackup($backupDir, $bucket);
        $actionHandler = $container->get(ActionHandler::class);
        $maintainer = $container->get(Maintainer::class);
        return new CacheServer($ioHandler, $restoredBucket, $actionHandler, $maintainer);
    }
    
    private function restoreFromBackup($backupDir, $bucket)
    {
        if(! file_exists($backupDir)) {
            return $bucket;
        }
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
            }
        }
        return $bucket;
    }
}
