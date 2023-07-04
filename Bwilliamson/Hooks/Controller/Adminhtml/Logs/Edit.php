<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\Logs;

use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageLogs;
use Bwilliamson\Hooks\Model\HistoryFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends AbstractManageLogs
{
    public PageFactory $resultPageFactory;

    public function __construct(
        HistoryFactory $historyFactory,
        Registry       $coreRegistry,
        Context        $context,
        PageFactory    $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;

        parent::__construct($historyFactory, $coreRegistry, $context);
    }

    public function execute()
    {
        $log = $this->initLog();
        if (!$log) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('bwhooks/logs/index');

            return $resultRedirect;
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Bwilliamson_Hooks::webhook');
        $resultPage->getConfig()->getTitle()->set(__('Log'));

        $title = __('View log %1', $log->getId());
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }
}
