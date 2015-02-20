<?php

namespace Gwc\App\Modules\Configurator\Model\Funnels;

class Office extends AbstractFunnel
{
    const TEMPLATE_SERVERS_POOL = '/\$servers_pool\s*=\s*Array\s*\(\s*((?:\s*[\'"][^\s]+[\'"]\s*=>\s*[\'"][^\s]+[\'"],?)+)/';
    const TEMPLATE_TEST_SERVER_NAME = '/\$test_server_name\s*=\s*\$servers_pool\s*\[\s*[\'"][^\s]+[\'"]\s*\]/';

    protected $name = 'office';

    /**
     * @param string $template
     * @return string mixed
     */
    public function match($template)
    {
        preg_match(static::TEMPLATE_SERVERS_POOL, $template, $serversPoolResult);
        preg_match(static::TEMPLATE_TEST_SERVER_NAME, $template, $testServerNameResult);

        $result = str_replace(array(
            $serversPoolResult[1],
            $testServerNameResult[0]
        ), array(
            $this->prepareServerList($this->parser->getConfigurator()->getJedi()),
            $this->prepareTestServerName()
        ), $template);

        return $result;
    }

    /**
     * @return string
     */
    protected function prepareTestServerName()
    {
        return '$test_server_name = $servers_pool["' . $this->getJediName() . '"]';
    }
}
