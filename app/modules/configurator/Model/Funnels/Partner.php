<?php

namespace Gwc\App\Modules\Configurator\Model\Funnels;

class Partner extends AbstractFunnel
{
    const IS_DEPRECATED = true;

    protected $name = 'partner';

    /**
     * Matches the funnel's config with the concrete template
     *
     * @param string $template
     * @return string mixed
     */
    public function match($template)
    {
        // TODO: Implement match() method.
    }
}
