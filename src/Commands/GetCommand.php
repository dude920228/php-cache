<?php

namespace PhpCache\Commands;

use PhpCache\CacheClient\CacheClient;
use PhpCache\ServiceManager\ServiceManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of PhpCache
 *
 * @author dude920228
 */
class GetCommand extends Command
{
    private $serviceManager;
    
    public function __construct($config, $name = null)
    {
        parent::__construct($name);
        $this->serviceManager = new ServiceManager($config);
    }
    protected function configure()
    {
        $this->setName('get');
        $this->setHelp('This command allows you to get entries by key');
        $this->addArgument('key', InputArgument::OPTIONAL, 'Key for the cache entry');
    }
    
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getEntry($input, $output);
    }
    
    private function getEntry(InputInterface $input, OutputInterface $output)
    {
        $key = $input->getArgument('key');
        /* @var $client CacheClient */
        $client = $this->serviceManager->get(CacheClient::class);
        $table = (new Table($output))->setHeaders(array('KEY', 'VALUE'));
        if(!is_null($key)) {
            $value = $client->get($key);
            if($value === false) {
                $output->writeln('<comment>No entry found for key: '.$key.'</comment>');
                return;
            }
            $table->setRows(array(array($key, $value)));
            $table->render();
            return;
        }
        $entries = $client->getEntries();
        $op = array();
        foreach($entries as $key => $value) {
            $op[] = array($key, $value);
        }
        $table->setRows($op);
        $table->render();
    }
}
