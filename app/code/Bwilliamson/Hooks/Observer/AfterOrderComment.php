<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;

class AfterOrderComment extends AfterSave
{

    protected string $hookType = HookType::NEW_ORDER_COMMENT;
}
