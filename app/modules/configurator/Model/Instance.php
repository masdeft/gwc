<?php

namespace Gwc\App\Modules\Configurator\Model;

use ArrayObject;

class Instance
{
    /**
     * The name of this instance, e.g. 'ca'
     * @var string
     */
    protected  $name;

    /**
     * The directory of this instance, e.g. 'HTML-rc'
     * @var string
     */
    protected $dir;

    /**
     * The list of all funnels
     * @var array
     */
    protected $funnels;

    public function __construct($name, $dir)
    {
        $this->name = (string) $name;
        $this->dir = (string) $dir;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @param array $funnels
     */
    public function setFunnels(array $funnels)
    {
        $this->funnels = new ArrayObject($funnels);
    }

    /**
     * @return array
     */
    public function getFunnels()
    {
        return $this->funnels;
    }
}
