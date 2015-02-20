<?php

namespace Gwc\Lib\Application;

use \Gwc\Lib\ModuleManager\ModuleManagerInterface;

interface BootstrapInterface
{
    /**
     * @param \Gwc\Lib\ModuleManager\ModuleManagerInterface $moduleManager
     * @return mixed
     */
    public function registerRoutes(ModuleManagerInterface $moduleManager);
}
