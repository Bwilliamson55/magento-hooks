<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;
use Magento\Framework\Event\Observer;

class AfterProduct extends AfterSave
{

    protected string $hookType = HookType::NEW_PRODUCT;
    protected string $hookTypeUpdate = HookType::UPDATE_PRODUCT;

    public function execute(Observer $observer)
    {
        $item = $observer->getDataObject();
        if ($item->getMpNew()) {
            parent::execute($observer);
        } else {
            $this->updateObserver($observer);
        }
    }
}
