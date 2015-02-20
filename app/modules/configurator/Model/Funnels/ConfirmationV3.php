<?php


namespace Gwc\App\Modules\Configurator\Model\Funnels;


class ConfirmationV3 extends AbstractFunnel
{
    /**
     * @var string
     */
    protected $name = 'confirmation_v3';

    const TEMPLATE_SERVERS_POOL = '/\$servers_pool\s*=\s*array\s*\(\s*((?:\s*[\'"][^\s]+[\'"]\s*=>\s*[\'"][^\s]+[\'"],?)+)/';
    const TEMPLATE_SERVER = '/\$servers_pool\s*\[\s*[\'"][^\s]+[\'"]\s*\]/';

    /**
     * Matches the funnel's config with the concrete template
     *
     * @param string $template
     * @return string mixed
     */
    public function match($template)
    {
        preg_match(self::TEMPLATE_SERVERS_POOL, $template, $serversPoolResult);
        preg_match(self::TEMPLATE_SERVER, $template, $serverResult);

        $serverList = $this->prepareServerList($this->parser->getConfigurator()->getJedi());

        $result = str_replace(array(
            $serversPoolResult[1],
            $serverResult[0]
        ), array(
            $serverList,
            $this->prepareServerName()
        ),  $template);

        return $result;
    }

    /**
     * @return string
     */
    protected function prepareServerName()
    {
        return '$servers_pool["' . $this->getJediName() . '"]';
    }
}