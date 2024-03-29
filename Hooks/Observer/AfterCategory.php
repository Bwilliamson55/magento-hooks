<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;
use Magento\Framework\Event\Observer;

class AfterCategory extends AfterSave
{

    protected string $hookType = HookType::NEW_CATEGORY;

    protected string $hookTypeUpdate = HookType::UPDATE_CATEGORY;

    public function execute(Observer $observer): void
    {
        $item = $observer->getDataObject();
        if ($item?->getBwItemIsNew()) {
            parent::execute($observer);
        } else {
            $this->updateObserver($observer);
        }
    }
}
