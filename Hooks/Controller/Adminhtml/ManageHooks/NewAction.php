<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Forward;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

class NewAction extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Bwilliamson_Hooks::manage_hooks_edit';

    public function __construct(
        Context        $context,
        private ForwardFactory $resultForwardFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): Forward
    {
        $resultForward = $this->resultForwardFactory->create();
        $resultForward->forward('edit');

        return $resultForward;
    }
}
