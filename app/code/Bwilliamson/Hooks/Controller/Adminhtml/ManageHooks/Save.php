<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Controller\Adminhtml\AbstractManageHooks;
use Bwilliamson\Hooks\Model\HookFactory;
use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\StoreManagerInterface;
use RuntimeException;

class Save extends AbstractManageHooks
{
    protected StoreManagerInterface $_storeManager;
    protected Json $json;

    public function __construct(
        HookFactory           $hookFactory,
        Registry              $coreRegistry,
        Context               $context,
        Json                  $jsonSerializer,
        StoreManagerInterface $storeManager
    )
    {
        $this->json = $jsonSerializer;
        $this->_storeManager = $storeManager;

        parent::__construct($hookFactory, $coreRegistry, $context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPost('hook');
        $hook = $this->initHook();

        if (is_array($data['headers'])) {
            unset($data['headers']['__empty']);
            $data['headers'] = $this->json->serialize($data['headers']);
        }

        if (isset($data['order_status']) && $data['order_status']) {
            $data['order_status'] = implode(',', $data['order_status']);
        }

        if (isset($data['store_ids']) && $data['store_ids'] && !$this->_storeManager->isSingleStoreMode()) {
            $data['store_ids'] = implode(',', $data['store_ids']);
        }

        $hook->addData($data);

        try {
            $hook->save();

            $this->messageManager->addSuccess(__('The hook has been saved.'));
            $this->_getSession()->setData('bwilliamson_hooks_hook_data', false);

            if ($this->getRequest()->getParam('back')) {
                $resultRedirect->setPath('bwhooks/*/edit', ['hook_id' => $hook->getId(), '_current' => true]);
            } else {
                $resultRedirect->setPath('bwhooks/*/');
            }

            return $resultRedirect;
        } catch (LocalizedException|RuntimeException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong while saving the Post.'));
        }

        $this->_getSession()->setData('bwilliamson_hooks_hook_data', $data);

        $resultRedirect->setPath('bwhooks/*/edit', ['hook_id' => $hook->getId(), '_current' => true]);

        return $resultRedirect;
    }
}
