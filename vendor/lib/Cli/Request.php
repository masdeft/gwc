<?php

namespace Gwc\Lib\Cli;

use Gwc\Lib\Cli\RequestInterface;
use ArrayObject;

class Request implements RequestInterface
{
    /**
     * @var array|null
     */
    protected $params = null;

    /**
     * @var array|null
     */
    protected $options = null;

    /**
     * @var string|null
     */
    protected $scriptName = null;

    public function __construct(array $args = null)
    {
        if ($args === null) {
            if (!isset($_SERVER['argv'])) {
                $errorDescription = (ini_get('register_argc_argv') == false)
                    ? "Cannot create Console\\Request because PHP ini option 'register_argc_argv' is set Off"
                    : 'Cannot create Console\\Request because $_SERVER["argv"] is not set for unknown reason.';
                throw new Exception($errorDescription);
            }
            $args = $_SERVER['argv'];
        }

        /**
         * Extract first param assuming it is the script name
         */
        if (count($args) > 0) {
            $this->setScriptName(array_shift($args));
        }

        $this->getParams()->exchangeArray($args);
    }

    /**
     * Exchange parameters object
     *
     * @param ArrayObject $params
     * @return Request
     */
    public function setParams(ArrayObject $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Return the container responsible for parameters
     *
     * @return ArrayObject
     */
    public function getParams()
    {
        if ($this->params === null) {
            $this->params = new ArrayObject();
        }

        return $this->params;
    }

    /**
     * @param string $scriptName
     */
    public function setScriptName($scriptName)
    {
        $this->scriptName = $scriptName;
    }

    /**
     * @return string
     */
    public function getScriptName()
    {
        return $this->scriptName;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        if ($this->options === null) {
            $this->options = getopt("h::o::i:", array(
                'help::',
                'output::',
                'instance:',
            ));
        }
        return $this->options;
    }

}
