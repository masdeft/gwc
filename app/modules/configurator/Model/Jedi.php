<?php

namespace Gwc\App\Modules\Configurator\Model;

class Jedi
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $link;

    /**
     * Constructor
     *
     * @param $name
     * @param $link
     */
    public function __construct($name, $link)
    {
        $this->name = $name;
        $this->link = $link;
    }

    /**
     * Gets the jedi name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the jedi DNS name
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}
