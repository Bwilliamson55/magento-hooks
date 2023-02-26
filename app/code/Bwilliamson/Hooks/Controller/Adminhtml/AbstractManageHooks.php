<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml;

use Bwilliamson\Hooks\Model\Hook;
use Bwilliamson\Hooks\Model\HookFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;

abstract class AbstractManageHooks extends Action
{
    /** Authorization level of a basic admin session */
    const ADMIN_RESOURCE = 'Bwilliamson_Hooks::webhooks';

    public HookFactory $hookFactory;
    public Registry $coreRegistry;

    /**
     * @param HookFactory $hookFactory
     * @param Registry $coreRegistry
     * @param Context $context
     */
    public function __construct(
        HookFactory $hookFactory,
        Registry    $coreRegistry,
        Context     $context
    )
    {
        $this->hookFactory = $hookFactory;
        $this->coreRegistry = $coreRegistry;

        parent::__construct($context);
    }

    protected function initHook(bool $register = false)
    {
        $hookId = $this->getRequest()->getParam('hook_id');

        /** @var Hook $hook */
        $hook = $this->hookFactory->create();

        if ($hookId) {
            $hook = $hook->load($hookId);
            if (!$hook->getId()) {
                $this->messageManager->addErrorMessage(__('This hook no longer exists.'));

                return false;
            }
        }
        if ($register) {
            $this->coreRegistry->register('bwilliamson_hooks_hook', $hook);
        }

        return $hook;
    }
}
