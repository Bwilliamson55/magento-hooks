<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;
use Magento\Framework\Event\Observer;

class AfterCustomer extends AfterSave
{
    protected string $hookType = HookType::NEW_CUSTOMER;
    protected string $hookTypeUpdate = HookType::UPDATE_CUSTOMER;
    protected int $i = 0;

    public function execute(Observer $observer): void
    {
        $item = $observer->getDataObject();
        if ($item->getBwItemIsNew()) {
            // Prevent duplicate sends for the same customer save event
            if ($this->i === 0) {
                parent::execute($observer);
            }
            $this->i++;
        } else {
            $this->updateObserver($observer);
        }
    }
}
