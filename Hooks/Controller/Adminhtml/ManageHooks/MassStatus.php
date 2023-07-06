<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Api\HooksRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class MassStatus extends Action
{
    private Filter $filter;
    private HooksRepositoryInterface $hooksRepository;

    public function __construct(
        Context           $context,
        Filter            $filter,
        HooksRepositoryInterface $hooksRepository
    ) {
        $this->filter = $filter;
        $this->hooksRepository = $hooksRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->hooksRepository->getCollection());

        $status = (int)$this->getRequest()->getParam('status');
        $hookUpdated = 0;
        foreach ($collection as $hook) {
            try {
                $hook->setStatus($status)
                    ->save();

                $hookUpdated++;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    __('Something went wrong while updating status for %1.', $hook->getName())
                );
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
