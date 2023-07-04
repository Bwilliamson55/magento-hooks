<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml;

use Bwilliamson\Hooks\Model\History;
use Bwilliamson\Hooks\Model\HistoryFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;

abstract class AbstractManageLogs extends Action
{
    /** Authorization level of a basic admin session */
    const ADMIN_RESOURCE = 'Bwilliamson_Hooks::webhooks';

    public HistoryFactory $historyFactory;
    public Registry $coreRegistry;

    public function __construct(
        HistoryFactory $historyFactory,
        Registry       $coreRegistry,
        Context        $context
    )
    {
        $this->historyFactory = $historyFactory;
        $this->coreRegistry = $coreRegistry;

        parent::__construct($context);
    }

    protected function initLog(): History
    {
        $logId = $this->getRequest()->getParam('id');

        /** @var History $log */
        $log = $this->historyFactory->create();

        if ($logId) {
            $log = $log->load($logId);
            if (!$log->getId()) {
                $this->messageManager->addErrorMessage(__('This log no longer exists.'));

                return false;
            }
        }
        $this->coreRegistry->register('bwilliamson_hooks_log', $log);

        return $log;
    }
}
