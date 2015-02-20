<?php

namespace Gwc\App\Modules\Configurator\Model;

use Gwc\Lib\Config\Reader\PhpFile as PhpFileReader;
use Gwc\App\Modules\Configurator\Model\Funnels\FunnelSpecialist;
use Gwc\App\Modules\Configurator\Model\Funnels\Funnel;

class Parser
{
    const RCSOAP_DIR = '/api/rcsoap/';

    /**
     * @var Funnels\FunnelSpecialist
     */
    protected $funnelSpecialist;

    /**
     * @var Configurator
     */
    protected $configurator;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @var \Gwc\Lib\Config\Reader\PhpFile
     */
    protected $phpFileReader;

    /**
     * @var array
     */
    protected $resultMessages = array();

    /**
     * @var array
     */
    protected $details;

    /**
     * @var Wsdl
     */
    protected $wsdl;

    /**
     * @param Configurator $configurator
     */
    public function __construct(Configurator $configurator)
    {
        $this->configurator = $configurator;
        $this->details = $configurator->getDetails();
        $this->funnelSpecialist = new FunnelSpecialist();
        $this->phpFileReader = new PhpFileReader();
    }

    /**
     * This function runs through the all funnels defined in configs.
     * Actually, it can catch some obvious errors, e.g. "There is no such funnel" or the funnel exists
     * but there is no a defined class for it.
     *
     * @throws \Exception
     */
    public function configure()
    {
        $instances = $this->configurator->getInstances();

        // Iterate all the instances defined in gwc.ini
        foreach ($instances as $instance) {
            $this->iterateFunnels($instance);
        }

        $this->resultMessages = array_merge($this->resultMessages, $this->errors);
    }

    /**
     * Iterate funnels of the concrete instance
     *
     * @param Instance $instance
     * @throws \Exception
     */
    protected function iterateFunnels(Instance $instance)
    {
        $funnels = $instance->getFunnels();

        foreach ($funnels as $funnelName => $jediName) {
            if (!$jediName) {
                $jediName = $this->details['default_jedi'];
            }
            try {
                if (!$this->funnelSpecialist->isFunnel($funnelName)) {
                    throw new \RuntimeException(
                        "The funnel '{$funnelName}' that you set in 'gwc.ini' does not exist."
                    );
                }
                $funnelClassName = $this->funnelSpecialist->getClass($funnelName);
                if (!$funnelClassName) {
                    throw new \RuntimeException(
                        "A class of the funnel '{$funnelName}' can not be found."
                    );
                }
                $funnelClassName = __NAMESPACE__ . '\\' . $funnelClassName;
                $funnel = new $funnelClassName($this);
                $funnel->setJediName($jediName)
                    ->setInstanceDir($instance->getDir())
                    ->configure();

                $this->flashMessage($this->addResultMessage("\033[32m" . date(" D M j G:i:s T Y ") . "Funnel '{$funnelName}' has been configured successfully for '{$instance->getName()}'" . "\033[39m"));
            } catch (\Exception $e) {
                $this->flashMessage($this->addResultMessage("\033[31m" . date(" D M j G:i:s T Y ") . $e->getMessage() . " for '{$instance->getName()}'" . "\033[39m"), true);
            }
        }
    }

    /**
     * Creates wsdl dir(s) for the current instance
     *
     * @param string|array $insWsdl
     * @param string $insDir
     */
    protected function createWsdlForInstance($insWsdl, $insDir)
    {
        $wsdl = new Wsdl();
        if (is_array($insWsdl)) {
            foreach ($insWsdl as $wsdlDir) {
                $wsdl->createWsdlDir($insDir, $wsdlDir);
            }
        } else {
            $wsdl->createWsdlDir($insDir, $insWsdl);
        }
    }

    /**
     * Flashes the messages while parsing configs.
     *
     * @param $message
     * @return void
     */
    protected function flashMessage($message)
    {
        if (isset($this->details['show_status_messages']) && (int)$this->details['show_status_messages'] === 1) {
            print($message . "\n");
        }
    }

    /**
     * @return Configurator
     */
    public function getConfigurator()
    {
        return $this->configurator;
    }

    /**
     * @return \Gwc\Lib\Config\Reader\PhpFile
     */
    public function getPhpFileReader()
    {
        return $this->phpFileReader;
    }

    /**
     * @return Funnels\FunnelSpecialist
     */
    public function getFunnelSpecialist()
    {
        return $this->funnelSpecialist;
    }

    /**
     * Adds messages to the result array.
     *
     * @param $message
     * @param bool $isError
     * @return string $message
     */
    public function addResultMessage($message, $isError = false)
    {
        // if it is the error, also add the message to the array.
        if ($isError) {
            $this->errors[] = $message;
        }
        $this->resultMessages[] = $message;

        return $message;
    }

    /**
     * Gets the all parser messages
     *
     * @return array
     */
    public function getResultMessages()
    {
        return $this->resultMessages;
    }
}
