<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class CustomerLogin extends AfterSave
{
    protected string $hookType = HookType::CUSTOMER_LOGIN;

    /**
     * @param Observer $observer
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer): void
    {
        $customer = $observer->getCustomer();
        $this->helper->send($customer, $this->hookType);
    }
}
