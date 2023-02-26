<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Model\Hook;
use Bwilliamson\Hooks\Model\HookFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use RuntimeException;

class InlineEdit extends Action
{
    public JsonFactory $jsonFactory;
    public HookFactory $hookFactory;

    public function __construct(
        Context     $context,
        JsonFactory $jsonFactory,
        HookFactory $hookFactory
    )
    {
        $this->jsonFactory = $jsonFactory;
        $this->hookFactory = $hookFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        $hookItems = $this->getRequest()->getParam('items', []);
        if (!empty($hookItems) && !$this->getRequest()->getParam('isAjax')) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        $key = array_keys($hookItems);
        $hookId = !empty($key) ? (int)$key[0] : '';
        /** @var Hook $hook */
        $hook = $this->hookFactory->create()->load($hookId);
        try {
            $hookData = $hookItems[$hookId];
            $hook->addData($hookData);
            $hook->save();
        } catch (LocalizedException|RuntimeException $e) {
            $messages[] = $this->getErrorWithHookId($hook, $e->getMessage());
            $error = true;
        } catch (Exception $e) {
            $messages[] = $this->getErrorWithHookId(
                $hook,
                __('Something went wrong while saving the Post.')
            );
            $error = true;
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add Hook id to error message
     *
     * @param Hook $hook
     * @param string $errorText
     *
     * @return string
     */
    public function getErrorWithHookId(Hook $hook, string $errorText): string
    {
        return '[Hook ID: ' . $hook->getId() . '] ' . $errorText;
    }
}
