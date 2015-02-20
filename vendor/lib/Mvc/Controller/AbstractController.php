<?php

namespace Gwc\Lib\Mvc\Controller;

use Gwc\Lib\Mvc\View\View;

abstract class AbstractController implements ControllerInterface
{
    protected $view;

    protected $request;

    public function __construct()
    {
        $this->view = new View();
        $this->init();
    }

    /**
     * The controller must contain the 'index' action by default
     * @return void
     */
    abstract public function indexAction();

    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * Some initialization before the action
     * @return void
     */
    public function init()
    {
    }

    public function render($template)
    {
        return $this->view->render($template);
    }

    public function setViewParam($name, $value = null)
    {
        $this->view->data[$name] = $value;
    }
}
