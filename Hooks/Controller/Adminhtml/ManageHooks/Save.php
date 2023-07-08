<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Api\HooksRepositoryInterface;
use Bwilliamson\Hooks\Api\HooksServiceInterface;
use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageHooks;
use Bwilliamson\Hooks\Model\HookFactory;
use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\StoreManagerInterface;
use RuntimeException;

class Save extends AbstractManageHooks
{
    private Json $json;

    public function __construct(
        Context                                $context,
        protected HooksServiceInterface       $hooksService,
        protected HooksRepositoryInterface    $hooksRepository,
        HookFactory                            $hookFactory,
        Json                                   $jsonSerializer,
        private readonly StoreManagerInterface $storeManager
    ) {
        $this->json = $jsonSerializer;
        parent::__construct($context, $this->hooksService, $this->hooksRepository, $hookFactory);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $postData = $this->getRequest()->getPostValue();
        $hook = $this->initHook();

        if (isset($postData['headers']) && is_array($postData['headers'])) {
            unset($postData['headers']['__empty']);
            $postData['headers'] = $this->json->serialize($postData['headers']);
        }

        if (isset($postData['order_status']) && $postData['order_status']) {
            $postData['order_status'] = implode(',', $postData['order_status']);
        }

        if (isset($postData['store_ids']) && $postData['store_ids'] && !$this->storeManager->isSingleStoreMode()) {
            $postData['store_ids'] = implode(',', $postData['store_ids']);
        }

        $hook->addData($postData);

        try {
            $this->hooksRepository->save($hook);
            $this->messageManager->addSuccessMessage(__('The hook has been saved.'));
            $this->hooksService->setValue('bwilliamson_hooks_hook_data', false);

            if ($this->getRequest()->getParam('back')) {
                $resultRedirect->setPath('bwhooks/*/edit', ['hook_id' => $hook->getId(), '_current' => true]);
            } else {
                $resultRedirect->setPath('bwhooks/*/');
            }

            return $resultRedirect;
        } catch (LocalizedException|RuntimeException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the hook.'));
        }

        $this->hooksService->setValue('bwilliamson_hooks_hook_data', $postData);
        $resultRedirect->setPath('bwhooks/*/edit', ['hook_id' => $hook->getId(), '_current' => true]);

        return $resultRedirect;
    }
}
