<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Api\HooksRepositoryInterface;
use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageHooks;
use Bwilliamson\Hooks\Api\HooksServiceInterface;
use Bwilliamson\Hooks\Model\HookFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

class Edit extends AbstractManageHooks implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Bwilliamson_Hooks::manage_hooks_edit';

    public function __construct(
        Context $context,
        HooksServiceInterface $hooksService,
        HooksRepositoryInterface $hooksRepository,
        HookFactory $hookFactory,
        private readonly PageFactory $resultPageFactory
    ) {
        parent::__construct($context, $hooksService, $hooksRepository, $hookFactory);
    }

    public function execute()
    {
        // Get the hook, or create it if it doesn't exist
        $hook = $this->initHook();
        if (!$hook) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('bwhooks/managehooks/index');

            return $resultRedirect;
        }
        // Restore previously entered form data from session if any
        $data = $this->_session->getData('bwilliamson_hooks_hook', true);
        if (!empty($data)) {
            $hook->setData($data);
        }
        // Register the hook in session data - this replaces the deprecated registry method
        $this->hooksService->setValue('bwilliamson_hooks_hook', $hook);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Bwilliamson_Hooks::webhook');
        $resultPage->getConfig()->getTitle()->set(__('Hook'));

        $title = $hook->getId() ? __('Edit %1 hook', $hook->getName()) : __('New Hook');
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }
}
