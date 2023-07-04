<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\Logs;

use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageLogs;
use Bwilliamson\Hooks\Model\History;
use Exception;

class Delete extends AbstractManageLogs
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $log = $this->initLog();
        if ($log->getId()) {
            try {
                /** @var History $log */
                $log->delete();

                $this->messageManager->addSuccess(__('The log has been deleted.'));
                $resultRedirect->setPath('bwhooks/*/');

                return $resultRedirect;
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $resultRedirect->setPath('bwhooks/*/edit', ['id' => $log->getId()]);

                return $resultRedirect;
            }
        }
        // display error message
        $this->messageManager->addError(__('The log to delete was not found.'));
        $resultRedirect->setPath('bwhooks/*/');

        return $resultRedirect;
    }
}
