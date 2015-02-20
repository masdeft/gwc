<?php

namespace Gwc\App\Modules\Configurator\Model\Funnels;

class SalesAgentV3 extends SalesAgent
{
    const TEMPLATE_SERVERS_POOL = '/\$servers_pool\s*=\s*array\s*\(\s*((?:\s*[\'"][^\s]+[\'"]\s*=>\s*[\'"][^\s]+[\'"],?)+)/';

    protected $name = 'sales_agent_v3';
}
