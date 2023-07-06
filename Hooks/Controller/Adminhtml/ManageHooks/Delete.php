<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageHooks;
use Exception;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action\Context;
use Bwilliamson\Hooks\Api\HooksRepositoryInterface;

class Delete extends AbstractManageHooks
{
    protected ?HooksRepositoryInterface $hooksRepository;

    public function __construct(
        Context $context,
        ?HooksRepositoryInterface $hooksRepository = null
    ) {
        parent::__construct($context);
        $this->hooksRepository = $hooksRepository;
    }

    public function execute(): ResultInterface|ResponseInterface|Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $hook = $this->initHook();
        if ($hook->getId()) {
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

        $resultRedirect->setPath('bwhooks/*/');

        return $resultRedirect;
    }
}
