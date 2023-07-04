<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Model\Config\Source\HookType;
use Magento\Framework\Event\Observer;
use Magento\Newsletter\Model\Subscriber as SubscriberMagento;

class Subscriber extends AfterSave
{
    protected string $hookType = HookType::SUBSCRIBER;

    public function execute(Observer $observer)
    {
        $item = $observer->getEvent()->getSubscriber();
        $subscriberStatus = $item->getSubscriberStatus();

        if ($subscriberStatus === SubscriberMagento::STATUS_UNSUBSCRIBED) {
            return $this;
        }

        $this->helper->send($item, $this->hookType);

        return $this;
    }
}
