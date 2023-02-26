<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;

class AfterShipment extends AfterSave
{
    protected string $hookType = HookType::NEW_SHIPMENT;
}
