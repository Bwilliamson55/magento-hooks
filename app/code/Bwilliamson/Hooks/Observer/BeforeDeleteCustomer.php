<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;

class BeforeDeleteCustomer extends AfterSave
{
    protected string $hookType = HookType::DELETE_CUSTOMER;
}
