<?php

namespace PhpCache\Commands;

use PhpCache\CacheClient\CacheClient;
use PhpCache\ServiceManager\ServiceManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Description of SetCommand.
 *
 * @author dude920228
 */
class SetCommand extends Command
{
    private $serviceManager;

    public function __construct($config, $name = null)
    {
        parent::__construct($name);
        $this->serviceManager = new ServiceManager($config);
    }

    protected function configure()
    {
        $this->setName('set');
        $this->setHelp('This command allows you to create entries in the cache pool');
        $this->addArgument('key', InputArgument::REQUIRED, 'The key assigned to the cache entry');
        $this->addArgument('value', InputArgument::REQUIRED, 'The value of the cache entry');
    }

    protected function execute($input, $output)
    {
        $this->set($input);
    }

    private function set($input)
    {
        /* @var $client CacheClient */
        $client = $this->serviceManager->get(CacheClient::class);
        $client->set($input->getArgument('key'), $input->getArgument('value'));
    }
}
