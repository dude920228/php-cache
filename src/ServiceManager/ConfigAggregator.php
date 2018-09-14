<?php

/*
 * All rights reserved © 2018 Legow Hosting Kft.
 */

namespace PhpCache\ServiceManager;

/**
 * This class enables to use multiple configuration files or owerwrite core settings.
 * Use this class to merge multiple config arrays before creating a ServiceManager instance.
 *
 * @author kdudas
 */
class ConfigAggregator
{
    private $configs;

    private $mergedConfig;

    public function __construct()
    {
        $this->configs = [];
        $this->mergedConfig = [];
    }

    public function addConfig($config)
    {
        $this->configs[] = $config;
    }

    public function getMergedConfig()
    {
        foreach ($this->configs as $config) {
            $this->mergedConfig = array_merge($this->mergedConfig, $config);
        }

        return $this->mergedConfig;
    }
}
