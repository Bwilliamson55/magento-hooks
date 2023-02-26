<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageHooks;
use Bwilliamson\Hooks\Model\HookFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends AbstractManageHooks
{
    public PageFactory $resultPageFactory;

    public function __construct(
        HookFactory $hookFactory,
        Registry    $coreRegistry,
        Context     $context,
        PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;

        parent::__construct($hookFactory, $coreRegistry, $context);
    }

    public function execute()
    {
        $hook = $this->initHook();
        if (!$hook) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('bwhooks/managehooks/index');

            return $resultRedirect;
        }

        $data = $this->_session->getData('bwilliamson_hooks_hook', true);
        if (!empty($data)) {
            $hook->setData($data);
        }

        $this->coreRegistry->register('bwilliamson_hooks_hook', $hook);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Bwilliamson_Hooks::webhook');
        $resultPage->getConfig()->getTitle()->set(__('Hook'));

        $title = $hook->getId() ? __('Edit %1 hook', $hook->getName()) : __('New Hook');
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }
}
