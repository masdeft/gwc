<?php

namespace Gwc\App\Modules\Configurator\Model\Funnels;

interface Funnel
{
    public function getName();

    public function setInstanceDir($instanceDir);

    public function getInstanceDir();

    public function setJediName($jediName);

    public function getJediName();

    public function isDeprecated();

    public function configure();
}
