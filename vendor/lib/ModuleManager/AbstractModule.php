<?php

namespace Gwc\Lib\ModuleManager;

abstract class AbstractModule implements ModuleInterface
{
    public function getNamespace()
    {
        $reflector = new \ReflectionClass($this);
        return $reflector->getNamespaceName();
    }

    abstract public function getName();
}
