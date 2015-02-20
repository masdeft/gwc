<?php

namespace Gwc\App\Modules\Configurator\Model;

class Wsdl
{
    const WSDL_DIR_CHMOD = 0775;
    const WSDL_DIR_CHOWN = 'ring_central:httpd';

    /**
     * The release version
     * @var string
     */
    protected $release;

    /**
     * @var string
     */
    protected $baseWsdlDir = 'api/rcsoap/';

    /**
     * Local wsdl are used for these funnels
     *
     * @var array
     */
    protected $funnels = array('us', 'ca', 'uk');

    /**
     * Constructor
     *
     * @param string $release
     * @param string $baseWsdlDir
     */
    public function __construct($release = null, $baseWsdlDir = null)
    {
        $this->release = $release;

        if ($baseWsdlDir !== null) {
            $this->baseWsdlDir = $baseWsdlDir;
        }
    }

    /**
     * Creates a dir for the local wsdl, e.g. 'japil'
     *
     * @param $instanceDir
     * @param $jedi
     * @return void
     */
    public function createWsdlDir($instanceDir, $jedi)
    {
        $wsdlDir = $instanceDir . DIRECTORY_SEPARATOR . $this->baseWsdlDir . DIRECTORY_SEPARATOR . $jedi;
        if (!is_dir($wsdlDir)) {
            mkdir($wsdlDir, static::WSDL_DIR_CHMOD);
        }
    }

    /**
     * Checks whether the instance has local wsdl
     *
     * @param $instanceName
     * @return bool
     */
    public function isLocal($instanceName)
    {
        return in_array($instanceName, $this->funnels);
    }
} 