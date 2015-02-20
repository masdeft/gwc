<?php

namespace Gwc\App\Modules\Configurator\Model;

use Gwc\Lib\Config\Reader\Ini;
use ArrayObject;

class Configurator
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $instances;

    /**
     * @var array
     */
    protected $jedi;

    /**
     * @var array
     */
    protected $messages;

    /**
     * @var string
     */
    protected $defaultJedi = 'japi';

    public function __construct(array $options = null)
    {
        if ($options !== null) {
            $this->options = $options;
        }
    }

    /**
     * Set options, like these -w "something", etc.
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Parse config/gwc.ini
     *
     * @param array instances
     * @throws \Exception
     */
    public function parseConfig(array $instances)
    {
        $this->addInstances($instances, $this->getConfig('funnels'));

        $this->addJedi($this->getConfig('jedi'));

        $this->configureFunnels();
    }

    /**
     * Set a main config file.
     *
     * @param string $config
     */
    public function setConfig($config)
    {
        $ini = new Ini();
        $this->config = $ini->fromFile($config);
    }

    /**
     * Get the defined config from './gwc.ini'
     *
     * @param $name
     * @return array
     * @throws \Exception
     */
    public function getConfig($name)
    {
        if (!isset($this->config[$name])) {
            throw new \RuntimeException("Config '{$name}' is not defined in 'gwc.ini'.");
        }

        return $this->config[$name];
    }

    /**
     * Add objects of Instances to array
     *
     * @param array $instances
     * @param array $funnels
     * @return Configurator
     * @throws \Exception
     */
    protected function addInstances(array $instances, array $funnels)
    {
        $this->instances = new ArrayObject();

        foreach ($instances as $instanceName => $instanceDir) {
            if (isset($funnels[$instanceName])) {
                $inst = new Instance($instanceName, $instanceDir);
                $inst->setFunnels($funnels[$instanceName]);
                $this->instances[] = $inst;
            } else {
                throw new \RuntimeException("There are no funnels for the instance '{$instanceName}'.");
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getInstances()
    {
        return $this->instances;
    }

    /**
     * @param array $jedi
     * @return Configurator
     */
    protected function addJedi(array $jedi)
    {
        $this->jedi = new ArrayObject();
        foreach ($jedi as $name => $link) {
            $this->jedi[$name] = new Jedi($name, $link);
        }

        $details = $this->getDetails();
        if ($details && isset($details['default_jedi'])) {
            $this->setDefaultJedi($details['default_jedi']);
        }

        return $this;
    }

    /**
     * Gets a jedi list
     *
     * @return array
     */
    public function getJedi()
    {
        return $this->jedi;
    }

    /**
     * Changes configs of each funnel
     *
     * @return void
     */
    protected function configureFunnels()
    {
        $parser = new Parser($this);
        $parser->configure();

        $this->messages = $parser->getResultMessages();
    }

    /**
     * Get configs from '[details]', returns false if exceptions is caught
     *
     * @return array
     */
    public function getDetails()
    {
        try {
            $details = $this->getConfig('details');
            return $details;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $jedi
     * @return Configurator
     */
    public function setDefaultJedi($jedi)
    {
        $this->defaultJedi = (string) $jedi;

        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
