<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\Logs;

use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageLogs;
use Bwilliamson\Hooks\Helper\Data;
use Bwilliamson\Hooks\Model\Config\Source\Status;
use Bwilliamson\Hooks\Model\History;
use Bwilliamson\Hooks\Model\HistoryFactory;
use Bwilliamson\Hooks\Model\HookFactory;
use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;

class Replay extends AbstractManageLogs
{
    protected HookFactory $hookFactory;
    protected Data $helperData;

    public function __construct(
        HistoryFactory $historyFactory,
        Registry       $coreRegistry,
        Context        $context,
        HookFactory    $hookFactory,
        Data           $helperData
    )
    {
        $this->hookFactory = $hookFactory;
        $this->helperData = $helperData;

        parent::__construct($historyFactory, $coreRegistry, $context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $log = $this->initLog();
        $resultRedirect->setPath('bwhooks/logs');
        if ($log->getId()) {
            try {
                $hookId = $log->getHookId();
                $hook = $this->hookFactory->create()->load($hookId);
                if (!$hook->getId()) {
                    $this->messageManager->addError('The Hook no longer exits');

                    return $resultRedirect;
                }
                /** @var History $log */
                $result = $this->helperData->sendHttpRequestFromHook($hook, false, $log);
                $log->setResponse($result['response']);
            } catch (Exception $e) {
                $result = [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
            if ($result['success'] === true) {
                $log->setStatus(Status::SUCCESS)->setMessage('');
                $this->messageManager->addSuccess(__('The log has been replay successful.'));
            } else {
                $message = __('Cannot replay the log, Please try again later.');
                $this->messageManager->addError($message);
                $log->setStatus(Status::ERROR)->setMessage($message);
            }
            $log->save();

            return $resultRedirect;
        }
        $this->messageManager->addError(__('The Log to replay was not found.'));

        return $resultRedirect;
    }
}
