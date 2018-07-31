<?php

namespace PhpCache\Commands;

use PhpCache\CacheClient\CacheClient;
use PhpCache\ServiceManager\ServiceManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of GetKeysCommand
 *
 * @author dude920228
 */
class GetKeysCommand extends Command
{
    
    private $serviceManager;
    
    public function __construct($config, $name = null)
    {
        parent::__construct($name);
        $this->serviceManager = new ServiceManager($config);
    }
    
    protected function configure()
    {
        $this->setName('keys');
        $this->setHelp('Use this command to list existing keys in the cache pool');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getKeys($output);
    }
    
    private function getKeys($output)
    {
        $table = new Table($output);
        /* @var $client CacheClient */
        $client = $this->serviceManager->get(CacheClient::class);
        $keys  = $client->getKeys();
        $table->setHeaders(array('KEYS'));
        foreach($keys as $key) {
            $table->addRows(array(array($key)));
        }
        $table->render();
    }
}
