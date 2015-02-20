<?php

namespace Gwc\Lib\Application;

use Gwc\Lib\Application\ApplicationInterface;
use Gwc\Lib\Loader\LoaderInterface;
use Gwc\Lib\ModuleManager\ModuleManagerInterface;

abstract class AbstractApplication implements ApplicationInterface
{

    /**
     * @var \Gwc\Lib\ModuleManager\ModuleManagerInterface
     */
    public $moduleManager;

    /**
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * @var array
     */
    protected $config;

    abstract public function run();

    /**
     * Set a loader, it is needed to run configurator
     *
     * @param \Gwc\Lib\Loader\LoaderInterface $loader
     * @return WebApplication
     */
    public function setLoader(LoaderInterface $loader)
    {
        $this->loader = $loader;

        return $this;
    }

    /**
     * Call a bootstrap
     *
     * @return mixed
     * @throws Exception
     */
    public function bootstrap()
    {
        $bootstrap = $this->loader->getBootstrap();
        if ($bootstrap) {
            return new $bootstrap($this);
        }
        return false;
    }

    /**
     * Set an application's module manager
     *
     * @param \Gwc\Lib\ModuleManager\ModuleManagerInterface $moduleManager
     */
    public function setModuleManager(ModuleManagerInterface $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
}
