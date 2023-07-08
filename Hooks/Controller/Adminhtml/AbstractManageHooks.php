<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml;

use Bwilliamson\Hooks\Api\HooksRepositoryInterface;
use Bwilliamson\Hooks\Api\HooksServiceInterface;
use Bwilliamson\Hooks\Model\Hook;
use Bwilliamson\Hooks\Model\HookFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;

abstract class AbstractManageHooks extends Action
{
    /** Authorization level of a basic admin session */
    const ADMIN_RESOURCE = 'Bwilliamson_Hooks::webhooks';

    /**
     * @param Context $context
     * @param HooksServiceInterface $hooksService
     * @param HooksRepositoryInterface $hooksRepository
     * @param HookFactory $hookFactory
     */
    public function __construct(
        Context                            $context,
        protected HooksServiceInterface    $hooksService,
        protected HooksRepositoryInterface $hooksRepository,
        protected HookFactory              $hookFactory
    ) {

        parent::__construct($context);
    }

    protected function initHook(bool $register = false): ?Hook
    {
        $hookId = (int) $this->getRequest()->getParam('hook_id');

        if ($hookId && $this->hooksRepository) {
            try {
                $hook = $this->hooksRepository->getById($hookId);
            } catch (NoSuchEntityException) {
                $this->messageManager->addErrorMessage(__('This hook no longer exists.'));
                return null;
            }
        } else {
            $hook = $this->hookFactory->create();
        }

        if ($register && $this->hooksService) {
            $this->hooksService->setValue('bwilliamson_hooks_hook', $hook);
        }

        return $hook;
    }
}
