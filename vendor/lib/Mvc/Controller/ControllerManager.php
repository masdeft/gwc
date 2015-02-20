<?php

namespace Gwc\Lib\Mvc\Controller;

class ControllerManager
{
    /**
     * @var string
     */
    protected $defaultController = 'index';

    /**
     * @var string
     */
    protected $defaultAction = 'index';

    /**
     * @var array
     */
    protected $controllers = array();

    public function addController(ControllerInterface $controller)
    {
        $this->controllers[] = $controller;
    }

}
