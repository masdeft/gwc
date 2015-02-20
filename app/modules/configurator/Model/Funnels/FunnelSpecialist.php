<?php

namespace Gwc\App\Modules\Configurator\Model\Funnels;

use Gwc\Lib\Config\Reader\Ini;

class FunnelSpecialist
{
    /**
     * @var array
     */
    protected $configs;

    /**
     * All the defined funnels
     *
     * @var array
     */
    protected $funnelsClasses =
        array(
            'userbased' => 'Funnels\Att',
            'userbased_sa' => 'Funnels\AttSa',
            'office' => 'Funnels\Office',
            'professional' => 'Funnels\Professional',
            'fax' => 'Funnels\Fax',
            'sales_agent' => 'Funnels\SalesAgent',
            'express_setup' => 'Funnels\ExpressSetup',
            'tokenization' => 'Funnels\Tokenization',
            'confirmation' => 'Funnels\Confirmation',
            'partner' => 'Funnels\Partner',
            'office_uk' => 'Funnels\OfficeUk',
            'office_uk_sa' => 'Funnels\OfficeUkSa',
            'mobile' => 'Funnels\Mobile',
            'sales_agent_v3' => 'Funnels\SalesAgentV3',
            'office_v4' => 'Funnels\OfficeV4',
            'confirmation_v3' => 'Funnels\ConfirmationV3',
            'bt_sales_agent' => 'Funnels\BTSalesAgent',
        );

    /**
     * @var array
     */
    protected $dependencies = array();

    /**
     * @var null|array
     */
    protected $registeredFunnels = null;

    public function __construct()
    {
        $ini = new Ini();
        $this->configs = $ini->fromFile(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config'
                                                                  . DIRECTORY_SEPARATOR . 'funnels.ini');
    }

    /**
     * Check whether the funnel exists
     *
     * @param $funnelName
     * @return bool
     */
    public function isFunnel($funnelName)
    {
        if (array_key_exists($funnelName, $this->funnelsClasses)) {
            return true;
        }
        return false;
    }

    /**
     * Get a class of the concrete funnel
     *
     * @param $funnelName
     * @return bool|string
     */
    public function getClass($funnelName)
    {
        if (array_key_exists($funnelName, $this->funnelsClasses)) {
            return $this->funnelsClasses[$funnelName];
        }
        return false;
    }

    /**
     * Gets a path to config
     *
     * @param $funnelName
     * @return bool|string
     */
    public function getConfig($funnelName)
    {
        if (array_key_exists($funnelName, $this->configs)) {
            return $this->configs[$funnelName];
        }
        return false;
    }

}
