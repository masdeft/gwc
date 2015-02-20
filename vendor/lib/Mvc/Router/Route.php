<?php

namespace Gwc\Lib\Mvc\Router;

use Gwc\Lib\Mvc\Controller\Controller;
use Gwc\Lib\Mvc\Controller\Action;
use Gwc\Lib\ModuleManager\ModuleInterface;

class Route
{
    /**
     * @var string The route pattern (e.g. "/books/:id")
     */
    protected $pattern;

    /**
     * @var mixed The route callable
     */
    protected $callable;

    /**
     * @var array Key-value array of URL parameters
     */
    protected $params = array();

    /**
     * @var array value array of URL parameter names
     */
    protected $paramNames = array();

    /**
     * @var array key array of URL parameter names with + at the end
     */
    protected $paramNamesPath = array();

    /**
     * @var ControllerInterface the controller of this route
     */
    protected $controller;

    /**
     * @var ActionInterface the action of this route
     */
    protected $action;

    /**
     * @var string The name of a module
     */
    protected $module;

    /**
     * @var string The type of the route
     */
    protected $type;

    /**
     * Constructor
     *
     * @param string $pattern  The URL pattern (e.g. "/books/:id")
     * @param mixed  $callable Anything that returns TRUE for is_callable()
     */
    public function __construct($pattern, $callable = null)
    {
        $this->setPattern($pattern);
        $this->setCallable($callable);
    }

    /**
     * Get route pattern
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Set route pattern
     *
     * @param  string $pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * Get route callable
     *
     * @return mixed
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * Set route callable
     *
     * @param  mixed $callable
     * @throws \InvalidArgumentException If argument is not callable
     */
    public function setCallable($callable)
    {
        if (!is_null($callable) && !is_callable($callable)) {
            throw new \InvalidArgumentException('Route callable must be callable');
        }

        $this->callable = $callable;
    }

    /**
     * Get route parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set route parameters
     *
     * @param  array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Set a controller
     *
     * @param string|null $controller
     */
    public function setController($controller = null)
    {
        $this->controller = new Controller($controller);
    }

    /**
     * Get a controller
     *
     * @return \Gwc\Lib\Mvc\Controller\Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set an action
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = new Action($action);
    }

    /**
     * Get an action name
     *
     * @return \Gwc\Lib\Mvc\Controller\Action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set a module name
     *
     * @param ModuleInterface $module
     */
    public function setModule(ModuleInterface $module)
    {
        $this->module = $module;
    }

    /**
     * @return null|string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Matches URI?
     *
     * Parse this route's pattern, and then compare it to an HTTP resource URI
     * This method was modeled after the techniques demonstrated by Dan Sosedoff at:
     *
     * http://blog.sosedoff.com/2009/09/20/rails-like-php-url-router/
     *
     * @param  string $resourceUri A Request URI
     * @return bool
     */
    public function matches($resourceUri)
    {
        //Convert URL params into regex patterns, construct a regex for this route, init params
        $patternAsRegex = preg_replace_callback(
            '#:([\w]+)\+?#',
            array($this, 'matchesCallback'),
            str_replace(')', ')?', (string) $this->pattern)
        );
        if (substr($this->pattern, -1) === '/') {
            $patternAsRegex .= '?';
        }
        //Remove a path to script
        if ($this->getType() === 'cli') {
            preg_match('/^[\.\w\/:\\\]*[\/\\\]([\w]+\.php)$/', $resourceUri, $resourceScriptMatch);
            if ($resourceScriptMatch) {
                $resourceUri = $resourceScriptMatch[1];
            }
        }
        //Cache URL params' names and values if this route matches the current HTTP request
        if (!preg_match('#^' . $patternAsRegex . '$#', $resourceUri, $paramValues)) {
            return false;
        }
        foreach ($this->paramNames as $name) {
            if (isset($paramValues[$name])) {
                if (isset($this->paramNamesPath[$name])) {
                    $this->params[$name] = explode('/', urldecode($paramValues[$name]));
                } else {
                    $this->params[$name] = urldecode($paramValues[$name]);
                }
            }
        }

        return true;
    }

    /**
     * Convert a URL parameter (e.g. ":id", ":id+") into a regular expression
     *
     * @param  array    URL parameters
     * @return string   Regular expression for URL parameter
     */
    protected function matchesCallback($m)
    {
        $this->paramNames[] = $m[1];
        if (isset($this->conditions[$m[1]])) {
            return '(?P<' . $m[1] . '>' . $this->conditions[$m[1]] . ')';
        }
        if (substr($m[0], -1) === '+') {
            $this->paramNamesPath[$m[1]] = 1;

            return '(?P<' . $m[1] . '>.+)';
        }

        return '(?P<' . $m[1] . '>[^/]+)';
    }
}
