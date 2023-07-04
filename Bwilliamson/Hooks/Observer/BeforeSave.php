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

    public function execute(Observer $observer)
    {
        if (!$this->helper->isEnabled()) {
            return;
        }
        $item = $observer->getDataObject();
        if ($item->isObjectNew()) {
            $item->setBwItemIsNew(1);
        }
    }
}
