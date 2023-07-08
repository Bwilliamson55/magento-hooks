<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Api\HooksRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use RuntimeException;

class InlineEdit extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Bwilliamson_Hooks::manage_hooks_edit';

    public function __construct(
        Context                                   $context,
        private readonly JsonFactory              $jsonFactory,
        private readonly HooksRepositoryInterface $hooksRepository
    ) {

        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        $hookItems = $this->getRequest()->getParam('items', []);
        $isAjax = $this->getRequest()->getParam('isAjax', false);
        if (!$isAjax || !count($hookItems)) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        $hookId = key($hookItems);
        try {
            $hook = $this->hooksRepository->getById((int) $hookId);
            $hookData = $hookItems[$hookId];
            $hook->addData($hookData);
            $this->hooksRepository->save($hook);
        } catch (NoSuchEntityException) {
            $messages[] = $this->getErrorWithHookId($hookId, __('Hook not found.'));
            $error = true;
        } catch (LocalizedException|RuntimeException $e) {
            $messages[] = $this->getErrorWithHookId($hookId, $e->getMessage());
            $error = true;
        } catch (Exception) {
            $messages[] = $this->getErrorWithHookId(
                $hookId,
                __('Something went wrong while saving the Hook.')
            );
            $error = true;
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add Hook ID to error message
     *
     * @param int $hookId
     * @param string $errorText
     *
     * @return string
     */
    public function getErrorWithHookId(int $hookId, string $errorText): string
    {
        return '[Hook ID: ' . $hookId . '] ' . $errorText;
    }
}
