<?php

namespace Gwc\App\Modules\Configurator\Model\Funnels;

use Gwc\App\Modules\Configurator\Model\Parser;
use Gwc\Lib\Config\Writer\PhpFile as PhpFileWriter;

abstract class AbstractFunnel implements Funnel
{
    const IS_DEPRECATED = false;

    /**
     * Funnel's name
     *
     * @var string
     */
    protected $name;

    /**
     * Instance's dir
      *
     * @var string
     */
    protected $instanceDir;

    /**
     * Jedi's name
     *
     * @var string
     */
    protected $jediName;

    /**
     * @var \Gwc\App\Modules\Configurator\Model\Parser
     */
    protected $parser;

    /**
     * Constructor
     *
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Prepare the new config and replace an old config.
     *
     * @return bool
     * @throws \Exception
     */
    public function configure()
    {
        $config = getcwd() . DIRECTORY_SEPARATOR . $this->getInstanceDir() . $this->parser->getFunnelSpecialist()->getConfig($this->getName());

        $result = $this->match($this->parser->getPhpFileReader()->fromFile($config));

        $phpFileWriter = new PhpFileWriter();

        return $phpFileWriter->toFile($config, $result);
    }

    /**
     * Prepare a key-value list(string) of servers as contents of a php array,
     * e.g. 'japi' => 'japi.ringcentral.com'
     *
     * @param array $jediList
     * @return string
     */
    protected function prepareServerList($jediList)
    {
        $list = '';
        foreach($jediList as $jedi) {
            $list .=  "'" . $jedi->getName() . "'" . ' => ' . "'http://" . $jedi->getLink() . "'" . ',' . "\n";
        }

        return rtrim($list);
    }

    /**
     * Matches the funnel's config with the concrete template
     *
     * @param string $template
     * @return string mixed
     */
    abstract public function match($template);

    /**
     * Get the funnel name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set a directory where this funnel is
     *
     * @param string $instanceDir
     * @return AbstractFunnel
     */
    public function setInstanceDir($instanceDir)
    {
        $this->instanceDir = $instanceDir;

        return $this;
    }

    /**
     * Gets the instance dir, e.g. 'HTML-rc'
     *
     * @return string
     */
    public function getInstanceDir()
    {
        return $this->instanceDir;
    }

    /**
     * Sets the jedi name, e.g. 'japi'
     *
     * @param $jediName
     * @return AbstractFunnel
     */
    public function setJediName($jediName)
    {
        $this->jediName = $jediName;

        return $this;
    }

    /**
     * Gets the jedi name
     *
     * @return string
     */
    public function getJediName()
    {
        return $this->jediName;
    }

    /**
     * Check whether the funnel is deprecated
     *
     * @return bool
     */
    public function isDeprecated()
    {
        return static::IS_DEPRECATED;
    }
}
