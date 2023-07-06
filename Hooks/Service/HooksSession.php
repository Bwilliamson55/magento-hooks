<?php

namespace Bwilliamson\Hooks\Service;

use Magento\Framework\Session\SessionManager;
class HooksSession extends SessionManager
{
    protected string $namespace = 'hooks_session';
}
