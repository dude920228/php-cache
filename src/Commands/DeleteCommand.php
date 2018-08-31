<?php

namespace PhpCache\Commands;

use PhpCache\CacheClient\CacheClient;
use PhpCache\ServiceManager\ServiceManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of PhpCache.
 *
 * @author dude920228
 */
class DeleteCommand extends Command
{
    private $serviceManager;

    public function __construct($config, $name = null)
    {
        parent::__construct($name);
        $this->serviceManager = new ServiceManager($config);
    }

    protected function configure()
    {
        $this->setName('delete');
        $this->setHelp('This command allows you to delete entries from the cache pool by key');
        $this->addArgument('key', InputArgument::REQUIRED, 'Key for the cache entry to be deleted');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->deleteEntry($input, $output);
    }

    private function deleteEntry(InputInterface $input, OutputInterface $output)
    {
        $key = $input->getArgument('key');
        /* @var $client CacheClient */
        $client = $this->serviceManager->get(CacheClient::class);
        $client->delete($key);
    }
}
