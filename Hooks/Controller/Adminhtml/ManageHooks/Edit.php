<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageHooks;
use Bwilliamson\Hooks\Api\HooksServiceInterface;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Edit extends AbstractManageHooks
{

    public function __construct(
        protected ?HooksServiceInterface $hooksService,
        Context                          $context,
        private readonly PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface|ResponseInterface|Page|Redirect
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
