<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;

class AfterCreditmemo extends AfterSave
{
    protected string $hookType = HookType::NEW_CREDITMEMO;
}
