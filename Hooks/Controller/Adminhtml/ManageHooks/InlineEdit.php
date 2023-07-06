<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Api\HooksRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use RuntimeException;

class InlineEdit extends Action
{
    private JsonFactory $jsonFactory;
    private HooksRepositoryInterface $hooksRepository;

    public function __construct(
        Context     $context,
        JsonFactory $jsonFactory,
        HooksRepositoryInterface $hooksRepository
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->hooksRepository = $hooksRepository;

        parent::__construct($context);
    }

    public function execute(): Json|ResultInterface|ResponseInterface
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
