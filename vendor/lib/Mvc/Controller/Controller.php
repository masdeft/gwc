<?php

namespace Gwc\Lib\Mvc\Controller;

class Controller implements ControllerInterface
{
    protected $name = 'IndexController';

    protected $actions = array();

    public function __construct($name = null)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    /**
     * Add an action to the controller
     *
     * @param ActionInterface|string $action
     */
    public function addAction($action = 'index')
    {
        if ($action instanceof ActionInterface) {
            $this->actions[$action->getName()] = $action;
        } elseif (is_string($action)) {
            $this->actions[$action] = new Action($action);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getActionByName($actionName)
    {
        if (isset($this->actions[$actionName])) {
            return $this->actions[$actionName];
        } else {
            throw new \Exception("There is no action with name '{$actionName}'");
        }
    }
}
