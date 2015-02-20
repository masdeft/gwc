<?php

namespace Gwc\App\Tests\Modules\Configurator;

use Gwc\App\Modules\Configurator\Module;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $module = new Module();
        $this->assertTrue($module->getName() === 'configurator');
    }
}
