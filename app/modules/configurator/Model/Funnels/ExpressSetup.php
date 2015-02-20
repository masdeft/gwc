<?php

namespace Gwc\App\Modules\Configurator\Model\Funnels;

class ExpressSetup extends AbstractFunnel
{
    const TEMPLATE_SERVER_LIST = '/[\'"]server[\'"]\s*=>\s*array\(\s*[\'"]list[\'"]\s*=>\s*array\(((?:\s*[\'"][^\s]+[\'"]\s*=>\s*[\'"][^\s]+[\'"],?)+)\s*\),?\s*([\'"]default[\'"]\s*=>\s*[\'"][^\s]+[\'"]),*\s*\),*\s*\),*\s*([\'"]service[\'"]\s*=>\s*[\'"][^\s]+[\'"])/';
    const DEFAULT_PROTOCOL = 'http';

    /**
     * @var string
     */
    protected $name = 'express_setup';

    /**
     * Matches the funnel's config with the template TEMPLATE_SERVER_LIST
     *
     * @param string $template
     * @return string|false
     * @throws \Exception
     */
    public function match($template)
    {
        preg_match(self::TEMPLATE_SERVER_LIST, $template, $serverListResult);

        if (!$serverListResult) {
            return false;
         }

        $result = str_replace(array(
            $serverListResult[1],
            $serverListResult[2],
            $serverListResult[3]
        ), array(
            $this->prepareServerList($this->parser->getConfigurator()->getJedi()),
            $this->prepareDefaultValue($this->getJediName()),
            $this->prepareService()
        ), $template);

        return $result;
    }

    /**
     * Prepare a template string of jedi value
     *
     * @param string $jediName
     * @return string
     */
    protected function prepareDefaultValue($jediName = 'japi')
    {
        return  "'default' => '{$jediName}'";
    }

    /**
     * Prepare a template string of service value
     *
     * @return string
     */
    protected function prepareService()
    {
        $service = $this->getServiceJedi();
        $details = $this->parser->getConfigurator()->getDetails();
        $protocol = static::DEFAULT_PROTOCOL;
        if (isset($details['protocol']['service'])) {
            $protocol = $details['protocol']['service'];
        }

        return  "'service' => '{$protocol}://{$service}'";
    }

    /**
     * Gets service(SWS/SWR) for obtained jedi
     *
     * @return string|bool
     * @throws \Exception
     */
    protected function getServiceJedi()
    {
        $serviceConfig = $this->parser->getConfigurator()->getConfig('service');
        $jediName = $this->getJediName();
        if (!isset($serviceConfig[$jediName])) {
            throw new \Exception("The service is not defined for the jedi '{$jediName}' for '{$this->name}'.");
        }
        $serviceName = $serviceConfig[$jediName];

        $jediList = $this->parser->getConfigurator()->getConfig('jedi');
        if (!isset($jediList[$serviceName])) {
            throw new \Exception("The service '{$serviceName}' was not found in a jedi list.");
        }

        return $jediList[$serviceName];
    }
}
