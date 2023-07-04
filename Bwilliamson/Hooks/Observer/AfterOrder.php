<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;

class AfterOrder extends AfterSave
{
    protected string $hookType = HookType::ORDER;
}
