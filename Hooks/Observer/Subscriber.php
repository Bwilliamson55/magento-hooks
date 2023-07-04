<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Newsletter\Model\Subscriber as SubscriberMagento;

class Subscriber extends AfterSave
{
    protected string $hookType = HookType::SUBSCRIBER;

    /**
     * @param Observer $observer
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer): void
    {
        $item = $observer->getEvent()->getSubscriber();
        $subscriberStatus = $item->getSubscriberStatus();

        if ($subscriberStatus === SubscriberMagento::STATUS_UNSUBSCRIBED) {
            return;
        }

        $this->helper->send($item, $this->hookType);
    }
}
