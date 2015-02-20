<?php

namespace Gwc\Lib\Application;

use Gwc\Lib\Config\Config;
use Gwc\Lib\Http\Request;
use Gwc\Lib\Mvc\Router\Router;
use Gwc\Lib\Mvc\Controller\AbstractController;

class WebApplication extends AbstractApplication
{

    /**
     * @var Gwc\Lib\Cli\Request
     */
    protected $request;

    /**
     * Constructor
     *
     * @param \Gwc\Lib\Config\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Run the application
     *
     * @throws \Exception
     * @return void
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
        $matchedRoutes = $router->getMatchedRoutes($request->getRequestUri());

        foreach ($matchedRoutes as $route) {
            $controllerClass = $route->getModule()->getNamespace() . "\\" . $route->getController()->getName();
            $controller = new $controllerClass();
            if (!$controller instanceof AbstractController) {
                throw new \Exception("Controller class must be extended of AbstractController");
            }
            $action = $route->getAction()->getMethodFromAction();
            if (!$request instanceof Request) {
                throw new \Exception("This request must be an instance of 'Gwc\Lib\Http\Request'");
            }
            $controller->setRequest($request);
            $controller->$action();
        }
    }

}
