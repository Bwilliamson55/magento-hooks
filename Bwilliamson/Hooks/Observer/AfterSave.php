<?php

namespace Bwilliamson\Hooks\Observer;

use Bwilliamson\Hooks\Helper\Data;
use Bwilliamson\Hooks\Model\HookFactory;
use Exception;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

abstract class AfterSave implements ObserverInterface
{
    protected HookFactory $hookFactory;
    protected Data $helper;
    protected string $hookType = '';
    protected string $hookTypeUpdate = '';
    protected ManagerInterface $messageManager;
    protected StoreManagerInterface $storeManager;

    public function __construct(
        HookFactory           $hookFactory,
        ManagerInterface      $messageManager,
        StoreManagerInterface $storeManager,
        Data                  $helper
    )
    {
        $this->hookFactory = $hookFactory;
        $this->helper = $helper;
        $this->messageManager = $messageManager;
        $this->storeManager = $storeManager;
    }

    /**
     * @param Observer $observer
     *
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        $item = $observer->getDataObject();
        $this->helper->send($item, $this->hookType);
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    protected function updateObserver($observer)
    {
        $item = $observer->getDataObject();
        $this->helper->send($item, $this->hookTypeUpdate);
    }
}
