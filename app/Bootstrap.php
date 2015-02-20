<?php

namespace Gwc\App;

use Gwc\Lib\Application\BootstrapInterface;
use Gwc\Lib\ModuleManager\ModuleManager;
use Gwc\Lib\Mvc\Router\Router;
use Gwc\Lib\Config\Reader\Ini;
use Gwc\Lib\Application\ApplicationInterface;
use Gwc\Lib\ModuleManager\ModuleManagerInterface;

/**
 * Bootstrap class
 *
 * Runs modules that are needed
 */
class Bootstrap implements BootstrapInterface
{

    protected $ini = null;

    public function __construct(ApplicationInterface $application)
    {
        $moduleManager = new ModuleManager($this->getIni()->fromFile(__DIR__ . '/config/modules.ini'));
        $moduleManager->loadModules();

        $application->setModuleManager($moduleManager);
    }

    /**
     * @param \Gwc\Lib\ModuleManager\ModuleManagerInterface $moduleManager
     * @return \Gwc\Lib\Mvc\Router\Router|false
     */
    public function registerRoutes(ModuleManagerInterface $moduleManager)
    {
        $router = new Router($moduleManager);

        //an array of routes defined in a config
        $routes = $this->getIni()->fromFile(__DIR__ . '/config/routes.ini');

        if (!$routes) {
            return false;
        }
        foreach ($routes as $route) {
            $router->map($route['pattern'], isset($route['type']) ? $route['type'] : 'http', array(
                'controller' => $route['controller'],
                'action' => $route['action'],
                'module' => $route['module'],
            ));
        }

        return $router;
    }

    /**
     * @return \Gwc\Lib\Config\Reader\Ini
     */
    public function getIni()
    {
        if ($this->ini === null) {
            return new Ini();
        }
        return $this->ini;
    }

}
