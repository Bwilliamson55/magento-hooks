<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class AfterProduct extends AfterSave
{

    protected string $hookType = HookType::NEW_PRODUCT;
    protected string $hookTypeUpdate = HookType::UPDATE_PRODUCT;

    /**
     * @param Observer $observer
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer): void
    {
        $item = $observer->getDataObject();
        if ($item->getBwItemIsNew()) {
            parent::execute($observer);
        } else {
            $this->updateObserver($observer);
        }
    }
}
