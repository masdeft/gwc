<?php

namespace Gwc\App\Modules\Configurator\Controller;

use Gwc\Lib\Mvc\Controller\AbstractController;

class IndexController extends AbstractController
{

    public function init()
    {
        $this->view->setTemplatesDirectory(dirname(__DIR__) . DIRECTORY_SEPARATOR . "View");
    }

    public function indexAction()
    {
        $this->setViewParam('test', 'view_var_test');

        $this->render("index.php");
    }
}
