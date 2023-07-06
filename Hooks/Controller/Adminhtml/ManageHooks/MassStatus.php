<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Api\HooksRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaBuilder;

class MassStatus extends Action
{
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private HooksRepositoryInterface $hooksRepository;

    public function __construct(
        Context           $context,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        HooksRepositoryInterface $hooksRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->hooksRepository = $hooksRepository;
        parent::__construct($context);
    }

    /**
     * @return Redirect|ResultInterface|ResponseInterface
     */
    public function execute(): Redirect|ResultInterface|ResponseInterface
    {
        $status = (int)$this->getRequest()->getParam('status');
        $hookUpdated = 0;

        $searchCriteria = $this->searchCriteriaBuilder->create();
        $hookCollection = $this->hooksRepository->getList($searchCriteria);

        foreach ($hookCollection as $hook) {
            try {
                $hook->setStatus($status);
                $this->hooksRepository->save($hook);
                $hookUpdated++;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception) {
                $this->messageManager->addErrorMessage(__('An error occurred while updating the status.'));
            }
        }

        if ($hookUpdated) {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been updated.', $hookUpdated));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}
