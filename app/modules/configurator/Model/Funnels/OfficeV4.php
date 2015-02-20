<?php

namespace Gwc\App\Modules\Configurator\Model\Funnels;

class OfficeV4 extends AbstractFunnel
{
    const TEMPLATE_SERVERS_POOL = '/\$servers_pool\s*=\s*array\s*\(\s*((?:\s*[\'"][^\s]+[\'"]\s*=>\s*[\'"][^\s]+[\'"],?)+)/';
    const TEMPLATE_DEFAULT_JEDI = '/\$default_jedi\s*=\s*[\'"][^\s]+[\'"]/';

    protected $name = 'office_v4';

    /**
     * Matches the funnel's config with the concrete template
     *
     * @param string $template
     * @return string
     */
    public function match($template)
    {
        preg_match(self::TEMPLATE_SERVERS_POOL, $template, $serversPoolResult);
        preg_match(self::TEMPLATE_DEFAULT_JEDI, $template, $defaultJediResult);

        $serverList = $this->prepareServerList($this->parser->getConfigurator()->getJedi());

        $result = str_replace(array(
            $serversPoolResult[1],
            $defaultJediResult[0]
        ), array(
            $serverList,
            $this->prepareDefaultJedi()
        ),  $template);

        return $result;
    }

    /**
     * @return string
     */
    protected function prepareDefaultJedi()
    {
        return '$default_jedi = "' . $this->getJediName() . '"';
    }
}
