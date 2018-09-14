<?php

namespace PhpCache\ServiceManager;

use PhpCache\ServiceManager\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Description of ServiceManager.
 *
 * @author dude920228
 */
class ServiceManager implements ContainerInterface
{
    private $aliases;
    private $config;
    private $factories;
    private $invokables;

    public function __construct($config)
    {
        $this->config = $config;
        $this->configure();
    }

    private function configure()
    {
        if (isset($this->config['services']['aliases'])) {
            $this->aliases = $this->config['services']['aliases'];
        } else {
            $this->aliases = [];
        }
        if (isset($this->config['services']['factories'])) {
            $this->factories = $this->config['services']['factories'];
        } else {
            $this->factories = [];
        }
        if (isset($this->config['services']['invokables'])) {
            $this->invokables = $this->config['services']['invokables'];
        } else {
            $this->invokables = [];
        }
    }

    public function get($id)
    {
        if ($this->has($id)) {
            $serviceType = $this->getServiceType($id);
            if ($serviceType == 'alias') {
                $service = $this->aliases[$id];

                return $this->get($service);
            }
            if ($serviceType == 'factory') {
                return (new $this->factories[$id]())($this);
            }
            if ($serviceType == 'invokable') {
                return new $this->invokables[$id]();
            }
        }

        throw new NotFoundException(
            sprintf('Service "%s" couldn\'t be created! Reason: service not found', $id)
        );
    }

    private function getServiceType($id)
    {
        if (array_key_exists($id, $this->aliases)) {
            return 'alias';
        }

        return array_key_exists($id, $this->factories) ? 'factory' : 'invokable';
    }

    public function getConfig()
    {
        return $this->config['config'];
    }

    public function has($id)
    {
        return array_key_exists($id, $this->aliases) ||
        array_key_exists($id, $this->factories) ||
        array_key_exists($id, $this->invokables);
    }
}
