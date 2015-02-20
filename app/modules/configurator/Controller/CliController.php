<?php

namespace Gwc\App\Modules\Configurator\Controller;

use Gwc\Lib\Mvc\Controller\AbstractController;
use Gwc\App\Modules\Configurator\Model\Configurator;

class CliController extends AbstractController
{
    protected $instances;

    /**
     * An initialization before actions
     *
     * @return void
     */
    public function init()
    {
        $this->view->setTemplatesDirectory(dirname(__DIR__) . DIRECTORY_SEPARATOR . "View");
    }

    public function indexAction()
    {
        $configurator = new Configurator();
        $configurator->setConfig(getcwd() . DIRECTORY_SEPARATOR . 'gwc.ini');

        $options = $this->request->getOptions();
        try {
            $configurator->setOptions($options);
            if (!$options) {
                throw new \UnexpectedValueException("Invalid parameter. Type php gwc.phar -i='{params..}'");
            }
            array_walk($options, array($this, "checkOption"));
            if (!empty($this->instances)) {
                $instances = $configurator->getConfig("instances");
                foreach ($this->instances as $k => $v) {
                    if (array_key_exists($k, $instances['dir'])) {
                        $this->instances[$k] = $instances["dir"][$k];
                    } else {
                        throw new \UnexpectedValueException("There is no '{$k}' instance in this location.");
                    }
                }
            }
            $configurator->parseConfig($this->instances);

            $this->render("cli.php");
        } catch(\Exception $e) {
            print("\033[31m" . date(" D M j G:i:s T Y ") . $e->getMessage() . "\033[39m" . "\n");
        }
    }

    /**
     * Checks whether options are applied
     *
     * @param mixed $v
     * @param string $k
     * @throws \Exception
     * @return void
     */
    public function checkOption($v, $k)
    {
        if ($k === "i") {
            $this->instances = array_flip($this->explodeInstances($v));
        } else {
            throw new \UnexpectedValueException("Invalid parameter '{$k}'.");
        }
        // ...
    }

    /**
     * @param $instancesSeparatedByComma
     * @return array|bool
     */
    public function explodeInstances($instancesSeparatedByComma)
    {
        if ($instancesSeparatedByComma) {
            return explode(",", $instancesSeparatedByComma);
        }

        return false;
    }
}
