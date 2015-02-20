<?php

namespace Gwc\Lib\Mvc\Router;

use Gwc\Lib\Mvc\Router\Route;
use Gwc\Lib\ModuleManager\ModuleManagerInterface;

class Router
{
    /**
     * @var array Lookup hash of all route objects
     */
    protected $routes;

    /**
     * @var ModuleManagerInterface
     */
    protected $moduleManager;

    /**
     * @var array Array of route objects that match the request URI (lazy-loaded)
     */
    protected $matchedRoutes;

    public function __construct(ModuleManagerInterface $moduleManager)
    {
        $this->moduleManager = $moduleManager;

        $this->routes = array();
    }

    /**
     * Return route objects that match the given HTTP method and URI
     *
     * @param $resourceUri The resource URI to match against
     * @return array
     */
    public function getMatchedRoutes($resourceUri)
    {
        if (is_null($this->matchedRoutes)) {
            $this->matchedRoutes = array();
            foreach ($this->routes as $route) {
                if ($route->matches($resourceUri)) {
                    $this->matchedRoutes[] = $route;
                }
            }
        }

        return $this->matchedRoutes;
    }

    /**
     * Map a route object
     *
     * @param string $pattern
     * @param string $type
     * @param array $params
     * @param mixed $callable
     * @throws \Exception
     */
    public function map($pattern, $type, array $params, $callable = null)
    {
        $route = new Route($pattern, $callable);

        $route->setType($type);

        $route->setController(array_key_exists('controller', $params) ? (string) $params['controller'] : null);
        $route->setAction(array_key_exists('action', $params) ? (string) $params['action'] : null);

        if (isset($params['module'])) {
            $module = $this->moduleManager->getModule($params['module']);
            if ($module === null) {
                throw new \Exception("Module '{$params['module']}' does not exist.");
            }
            $route->setModule($module);
        }

        $this->addRoute($route);
    }

    /**
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }
}
