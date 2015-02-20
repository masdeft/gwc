<?php

namespace Gwc\App\Tests\Modules\Configurator\Model;

use PHPUnit_Framework_TestCase;
use Gwc\Lib\Config\Reader\Ini;

class ConfiguratorTest extends PHPUnit_Framework_TestCase
{
    public $config;

    public function testParseConfig()
    {
        $mainConfig = __DIR__ . '/../../../../../gwc.ini';
        $this->assertFileExists($mainConfig);

        $ini = new Ini();
        $this->config = $ini->fromFile($mainConfig);

        $this->assertArrayHasKey("instances", $this->config);
        $this->assertArrayHasKey("funnels", $this->config);
        $this->assertArrayHasKey("jedi", $this->config);
        $this->assertArrayHasKey("service", $this->config);
        $this->assertArrayHasKey("wsdl", $this->config);
        $this->assertArrayHasKey("details", $this->config);
    }
}
