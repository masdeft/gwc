<?php

namespace Gwc\Lib\Mvc\Controller;

interface ActionInterface
{
    public function setName($name);

    public function getName();

    public function setParams(array $params);

    public function getParams();
}
