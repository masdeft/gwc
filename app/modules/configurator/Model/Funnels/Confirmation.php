<?php

namespace Gwc\App\Modules\Configurator\Model\Funnels;

class Confirmation extends AbstractFunnel
{
    const IS_DEPRECATED = true;

    const TEMPLATE_SERVER_SITE_POOL = '/\$sever_site_pool\s*=\s*Array\s*\(\s*((?:\s*[\'"][^\s]+[\'"]\s*=>\s*[\'"][^\s]+[\'"],?)+)/';
    const TEMPLATE_SERVERS_POOL = '/\$servers_pool\s*=\s*Array\s*\(\s*((?:\s*[\'"][^\s]+[\'"]\s*=>\s*[\'"][^\s]+[\'"],?)+)/';
    const TEMPLATE_DEFAULT_JEDI = '/\$conf\s*\[\s*[\'"]default_jedi[\'"]\s*\]\s*=\s*[\'"][^\s]+[\'"]/';
    const TEMPLATE_TEST_SERVER_NAME = '/\$test_server_name\s*=\s*\$servers_pool\s*\[\s*[\'"][^\s]+[\'"]\s*\]/';

    protected $name = 'confirmation';

    /**
     * Matches the funnel's config with the concrete template
     *
     * @param string $template
     * @return string
     */
    public function match($template)
    {
        preg_match(self::TEMPLATE_SERVER_SITE_POOL, $template, $serverSitePoolResult);
        preg_match(self::TEMPLATE_SERVERS_POOL, $template, $serversPoolResult);
        preg_match(self::TEMPLATE_DEFAULT_JEDI, $template, $defaultJediResult);
        preg_match(self::TEMPLATE_TEST_SERVER_NAME, $template, $testServerNameResult);

        $serverList = $this->prepareServerList($this->parser->getConfigurator()->getJedi());

        $result = str_replace(array(
            $serverSitePoolResult[1],
            $serversPoolResult[1],
            $defaultJediResult[0],
            $testServerNameResult[0]
        ), array(
            $serverList,
            $serverList,
            $this->prepareDefaultJedi(),
            $this->prepareTestServerName()
        ),  $template);

        return $result;
    }

    /**
     * @return string
     */
    protected function prepareDefaultJedi()
    {
        return '$conf[\'default_jedi\'] = "' . $this->getJediName() . '"';
    }

    /**
     * @return string
     */
    protected function prepareTestServerName()
    {
        return '$test_server_name = $servers_pool["' . $this->getJediName() . '"]';
    }
}
