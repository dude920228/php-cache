<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\Commands;

use PhpCache\CacheClient\CacheClient;
use PhpCache\ServiceManager\ServiceManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of QuitCommand.
 *
 * @author kdudas
 */
class QuitCommand extends Command
{
    private $serviceManager;

    public function __construct($config, $name = null)
    {
        parent::__construct($name);
        $this->serviceManager = new ServiceManager($config);
    }

    protected function configure()
    {
        $this->setName('quit');
        $this->setHelp('Close the server process');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->quit($input, $output);
    }

    private function quit(InputInterface $input, OutputInterface $output)
    {
        /* @var $client CacheClient */
        $client = $this->serviceManager->get(CacheClient::class);
        $client->quitServer();
    }
}
