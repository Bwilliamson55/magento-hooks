<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageHooks;
use Bwilliamson\Hooks\Model\Hook;
use Exception;

class Delete extends AbstractManageHooks
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $hook = $this->initHook();
        if ($hook->getId()) {
            try {
                /** @var Hook $hook */
                $hook->delete();

                $this->messageManager->addSuccess(__('The Hook has been deleted.'));
                $resultRedirect->setPath('bwhooks/*/');

                return $resultRedirect;
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $resultRedirect->setPath('bwhooks/*/edit', ['hook_id' => $hook->getId()]);

                return $resultRedirect;
            }
        }
        $this->messageManager->addError(__('The Hook to delete was not found.'));
        $resultRedirect->setPath('bwhooks/*/');

        return $resultRedirect;
    }
}
