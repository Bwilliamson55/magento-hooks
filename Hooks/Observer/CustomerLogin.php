<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;
use Magento\Framework\Event\Observer;

class CustomerLogin extends AfterSave
{
    protected string $hookType = HookType::CUSTOMER_LOGIN;

    public function execute(Observer $observer)
    {
        $item = $observer->getCustomer();
        $this->helper->send($item, $this->hookType);
    }
}
