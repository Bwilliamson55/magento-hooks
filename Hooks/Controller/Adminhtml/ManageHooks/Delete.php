<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Api\HooksServiceInterface;
use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageHooks;
use Bwilliamson\Hooks\Model\HookFactory;
use Exception;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action\Context;
use Bwilliamson\Hooks\Api\HooksRepositoryInterface;

class Delete extends AbstractManageHooks implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Bwilliamson_Hooks::manage_hooks_delete';

    public function __construct(
        Context                             $context,
        HooksServiceInterface              $hooksService,
        protected HooksRepositoryInterface $hooksRepository,
        HookFactory                            $hookFactory
    ) {
        parent::__construct($context, $hooksService, $hooksRepository, $hookFactory);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $hook = $this->initHook();
        if ($hook?->getId()) {
            try {
                $this->hooksRepository->deleteById($hook->getId());
                $this->messageManager->addSuccessMessage(__('The Hook has been deleted.'));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception) {
                $this->messageManager->addErrorMessage(__('An error occurred while deleting the Hook.'));
            }
        } else {
            $this->messageManager->addErrorMessage(__('The Hook to delete was not found.'));
        }

        $resultRedirect->setPath('*/*/');

        return $resultRedirect;
    }
}
