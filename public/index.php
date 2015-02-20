<?php

namespace Gwc
{
    require '../vendor/lib/Loader/Loader.php';

    use Gwc\Lib\Loader\Loader,
        Gwc\Lib\Config\Reader\Ini,
        Gwc\Lib\Application\WebApplication,
        Gwc\Lib\Config\Config;

    // We have to register the loader.
    // $loader->register(array(
    //     'some_name' => array(
    //          'dir' => 'some_dir',
    //          'namespace' => 'Some\Your\Namespace'
    //          ...
    $loader = new Loader();
    $loader->register(array(
        'vendor' => array(
            'dir' => __DIR__ . '/../vendor/lib',
            'namespace' => 'Gwc\Lib'
        ),
        'configurator' => array(
            'dir' => __DIR__ . '/../app/modules/configurator',
            'namespace' => 'Gwc\App\Modules\Configurator'
        ),
        'logviewer' => array(
            'dir' => __DIR__ . '/../app/modules/logviewer',
            'namespace' => 'Gwc\App\Modules\LogViewer'
        ),
        'app' => array(
            'dir' => __DIR__ . '/../app',
            'namespace' => 'Gwc\App'
        ),
    ));
    $loader->setBootstrap('\Gwc\App\Bootstrap');

    $ini = new Ini();

    $application = new WebApplication(
        new Config($ini->fromFile(__DIR__ . '/../app/config/application.ini'))
    );
    // Loader is required for running
    $application->setLoader($loader)->run();

}