<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Api\HooksRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class MassDelete extends Action
{
    private HooksRepositoryInterface $hooksRepository;

    public function __construct(
        Context           $context,
        HooksRepositoryInterface $hooksRepository
    ) {
        $this->hooksRepository = $hooksRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $hookIds = $this->getRequest()->getParam('selected');
        if (!is_array($hookIds)) {
            $hookIds = [];
        }

        try {
            $this->hooksRepository->deleteByIds($hookIds);
            $this->messageManager->addSuccessMessage(__('Hooks have been deleted.'));
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('One or more hooks were not found.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong while deleting hooks.'));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}
