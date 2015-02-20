<?php

namespace Gwc\Lib\Mvc\Controller;

class Action implements ActionInterface
{
    /**
     * @var null|string an action name
     */
    protected $name = 'index';

    /**
     * @var array
     */
    protected $params = array();

    public function __construct($name = null)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Transform an "action" token into a method name
     *
     * @return string
     */
    public function getMethodFromAction()
    {
        $method  = str_replace(array('.', '-', '_'), ' ', $this->name);
        $method  = ucwords($method);
        $method  = str_replace(' ', '', $method);
        $method  = lcfirst($method);
        $method .= 'Action';

        return $method;
    }
}
