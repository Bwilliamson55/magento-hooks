<?php

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Api\HooksRepositoryInterface;
use Bwilliamson\Hooks\Model\ResourceModel\Hook\CollectionFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class MassStatus extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Bwilliamson_Hooks::manage_hooks_edit';
    public function __construct(
        Context           $context,
        private readonly CollectionFactory $collectionFactory,
        private readonly Filter $filter
    ) {
        parent::__construct($context);
    }

    /**
     * @throws LocalizedException
     */
    public function execute()
    {
        $collection = $this->collectionFactory->create();
        $items = $this->filter->getCollection($collection);
        $itemCount = $items->getSize();
        try {
            foreach ($items as $item) {
                $item->setStatus((int) $this->getRequest()->getParam('enable'));
                $item->save();
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been updated.', $itemCount));
        } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
        } catch (Exception) {
                $this->messageManager->addErrorMessage(__('An error occurred while updating the status.'));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}
