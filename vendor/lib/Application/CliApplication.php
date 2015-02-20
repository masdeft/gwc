<?php

namespace Gwc\Lib\Application;

use Gwc\Lib\Config\Config;
use Gwc\Lib\Cli\Request;
use Gwc\Lib\Mvc\Router\Router;
use Gwc\Lib\Mvc\Controller\AbstractController;

class CliApplication extends AbstractApplication
{
    /**
     * @var \Gwc\Lib\Cli\Request
     */
    protected $request;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Register routes and call a process of the request
     *
     * @throws \Exception
     */
    public function run()
    {
        if (!$this->loader) {
            throw new \Exception("a loader must be set");
        }

        if (!$bootstrap = $this->bootstrap()) {
            // TODO: Implement a run without Bootstrap

            throw new \Exception("the Bootstrap is required");
        }
        $router = $bootstrap->registerRoutes($this->moduleManager);
        $this->processRequest($router);
    }

    public function processRequest(Router $router)
    {
        $request = new Request();
        $matchedRoutes = $router->getMatchedRoutes($request->getScriptName());

        foreach ($matchedRoutes as $route) {
            $controllerClass = $route->getModule()->getNamespace() . "\\" . $route->getController()->getName();
            $controller = new $controllerClass();
            if (!$controller instanceof AbstractController) {
                throw new \Exception("Controller class must be extended of AbstractController");
            }
            $action = $route->getAction()->getMethodFromAction();
            if (!$request instanceof Request) {
                throw new \Exception("This request must be an instance of 'Gwc\Lib\Cli\Request'");
            }
            $controller->setRequest($request);
            $controller->$action();
        }
    }

    /**
     * Get the request object
     *
     * @return \Gwc\Lib\Cli\Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
