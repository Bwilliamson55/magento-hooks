<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Helper\Data;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class BeforeSave implements ObserverInterface
{
    protected Data $helper;

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if (!$this->helper->isEnabled()) {
            return;
        }
        // Persist newness to after save observers (e.g. AfterProduct)
        $item = $observer->getDataObject();
        if ($item->isObjectNew()) {
            $item->setBwItemIsNew(1);
        }
    }
}
