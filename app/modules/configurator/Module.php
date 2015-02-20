<?php

namespace Gwc\App\Modules\Configurator;

use Gwc\Lib\Mvc\Router\Router;
use Gwc\Lib\ModuleManager\ModuleInterface;
use Gwc\Lib\ModuleManager\AbstractModule;

class Module extends AbstractModule
{
    protected $name = 'configurator';

    public function __construct()
    {

    }

    public function getName()
    {
        return $this->name;
    }
}
