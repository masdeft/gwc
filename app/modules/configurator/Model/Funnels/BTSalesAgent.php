<?php

namespace Gwc\App\Modules\Configurator\Model\Funnels;

class BTSalesAgent extends AbstractFunnel
{
    const TEMPLATE_SERVER_NAME = '/\$env\s*=\s*[\'"][^\s]+[\'"]\s*;/';
    const TEMPLATE_SERVERS_POOL = '/\$config\s*\=\s*array\s*\(\s*((?:\s*[\'"][^\s]+[\'"]\s*=>\s*[\'"][^\s]+[\'"],?)+)/';

    /**
     * @var string
     */
    protected $name = 'bt_sales_agent';

    /**
     * Matches the funnel's config with the concrete template
     *
     * @param string $template
     * @return string mixed
     */
    public function match($template)
    {
        preg_match(self::TEMPLATE_SERVERS_POOL, $template, $serversPoolResult);
        preg_match(self::TEMPLATE_SERVER_NAME, $template, $serverNameResult);

        $result = str_replace(array(
            $serversPoolResult[1],
            $serverNameResult[0]
        ), array(
            $this->prepareServerList($this->parser->getConfigurator()->getJedi()),
            $this->prepareServerName($this->getJediName())
        ), $template);

        return $result;
    }

    /**
     * @param string $jediName
     * @return string
     */
    protected function prepareServerName($jediName = 'japi')
    {
        return '$env = "' . $jediName . '";';
    }
}