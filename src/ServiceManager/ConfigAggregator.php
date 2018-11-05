<?php

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

    public function addConfig(array $config): void
    {
        $this->configs[] = $config;
    }

    public function getMergedConfig(): array
    {
        foreach ($this->configs as $config) {
            $this->mergedConfig = array_merge_recursive($this->mergedConfig, $config);
        }

        return $this->mergedConfig;
    }
}
