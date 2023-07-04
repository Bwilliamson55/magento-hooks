<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\Logs;

use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageLogs;
use Bwilliamson\Hooks\Model\History;
use Exception;
use Magento\Framework\Exception\LocalizedException;
use RuntimeException;

class Save extends AbstractManageLogs
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPost('log');
        /** @var History $log */
        $log = $this->initLog();

        $log->setBody($data['body']);

        try {
            $log->save();

            $this->messageManager->addSuccess(__('The log has been saved.'));
            $this->_getSession()->setData('bwilliamson_hooks_log', false);
        } catch (LocalizedException|RuntimeException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong while saving the Log.'));
        }

        $resultRedirect->setPath('bwhooks/*/edit', ['id' => $log->getId(), '_current' => true]);

        return $resultRedirect;
    }
}
