<?php

namespace Gwc\App\Modules\Configurator\Model\Funnels;

class Mobile extends AbstractFunnel
{
    const TEMPLATE_SERVER_SITE_POOL = '/\$soapinfo\s*\[\s*[\'"]sever_site_pool[\'"]\s*\]\s*=\s*array\s*\(\s*((?:\s*[\'"][^\s]+[\'"]\s*=>\s*[\'"][^\s]+[\'"],?)+)/';
    const TEMPLATE_SERVERS_POOL = '/\$soapinfo\s*\[\s*[\'"]servers_pool[\'"]\s*\]\s*=\s*array\s*\(\s*((?:\s*[\'"][^\s]+[\'"]\s*=>\s*[\'"][^\s]+[\'"],?)+)/';
    const TEMPLATE_DEFAULT_JEDI = '/\$soapinfo\s*\[\s*[\'"]default_jedi[\'"]\s*\]\s*=\s*[\'"][^\s]+[\'"]/';

    protected $name = 'mobile';

    /**
     * Matches the funnel's config with the concrete template
     *
     * @param string $template
     * @return string mixed
     */
    public function match($template)
    {
        preg_match(self::TEMPLATE_SERVER_SITE_POOL, $template, $serverSitePoolResult);
        preg_match(self::TEMPLATE_SERVERS_POOL, $template, $serversPoolResult);
        preg_match(self::TEMPLATE_DEFAULT_JEDI, $template, $defaultJediResult);

        $serverList = $this->prepareServerList($this->parser->getConfigurator()->getJedi());

        $result = str_replace(array(
            $serverSitePoolResult[1],
            $serversPoolResult[1],
            $defaultJediResult[0]
        ), array(
            $serverList,
            $serverList,
            $this->prepareDefaultJedi()
        ),  $template);

        return $result;
    }

    /**
     * Prepares a string to replace it in the template
     *
     * @return string
     */
    protected function prepareDefaultJedi()
    {
        return '$soapinfo[\'default_jedi\'] = "' . $this->getJediName() . '"';
    }
}
