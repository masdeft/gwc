<?php

namespace Gwc\App\Tests;

use PHPUnit_Framework_TestCase;
use Gwc\Lib\ModuleManager\ModuleManager;
use Gwc\Lib\ModuleManager\ModuleManagerInterface;
use Gwc\Lib\Application\WebApplication;
use Gwc\Lib\Application\CliApplication;
use Gwc\Lib\Config\Config;
use Gwc\Lib\Config\Reader\Ini;
use Gwc\Lib\Mvc\Router\Router;

class BootstrapTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ini
     */
    protected $ini;

    public function setUp()
    {
        $this->ini = new Ini();
    }

    /**
     * @return array
     */
    public function testPrepareApplicationConfig()
    {
        $applicationConfigFile = __DIR__ . '/../config/application.ini';
        $this->assertFileExists($applicationConfigFile);
        return $this->ini->fromFile($applicationConfigFile);
    }

    /**
     * @return array
     */
    public function testPrepareModulesConfig()
    {
        $modulesConfigFile = __DIR__ . '/../config/modules.ini';
        $this->assertFileExists($modulesConfigFile);
        $modulesConfigArray = $this->ini->fromFile($modulesConfigFile);
        $this->assertNotEmpty($modulesConfigArray);
        return $modulesConfigArray;
    }

    /**
     * @depends testPrepareApplicationConfig
     * @depends testPrepareModulesConfig
     */
    public function testWebApplicationBootstrap(array $applicationConfig, array $modulesConfig)
    {
        $moduleManager = new ModuleManager($modulesConfig);
        $moduleManager->loadModules();
        $this->assertTrue($moduleManager->checkWhetherModulesAreLoaded());

        $application = new WebApplication(new Config($applicationConfig));
        $application->setModuleManager($moduleManager);
    }

    /**
     * @depends testPrepareApplicationConfig
     * @depends testPrepareModulesConfig
     */
    public function testCliApplicationBootstrap(array $applicationConfig, array $modulesConfig)
    {
        $moduleManager = new ModuleManager($modulesConfig);
        $moduleManager->loadModules();
        $this->assertTrue($moduleManager->checkWhetherModulesAreLoaded());

        $application = new CliApplication(new Config($applicationConfig));
        $application->setModuleManager($moduleManager);
    }

    /**
     * @return \Gwc\Lib\ModuleManager\ModuleManager
     */
    public function testSetModuleManagerToApplication()
    {
        $moduleManager = new ModuleManager(array('configurator' => 'Gwc\App\Modules\Configurator'));

        return $moduleManager;
    }

    /**
     * @depends testSetModuleManagerToApplication
     */
    public function testRegisterRoutes(ModuleManagerInterface $moduleManager)
    {
        $router = new Router($moduleManager);
        $routesConfigFile = __DIR__ . '/../config/routes.ini';
        $this->assertFileExists($routesConfigFile);
        $this->assertNotEmpty($this->ini->fromFile($routesConfigFile));
    }

}
