<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;

class BeforeDeleteProduct extends AfterSave
{
    protected string $hookType = HookType::DELETE_PRODUCT;
}
