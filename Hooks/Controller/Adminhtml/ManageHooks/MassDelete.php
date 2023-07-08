<?php declare(strict_types=1);

namespace Bwilliamson\Hooks\Controller\Adminhtml\ManageHooks;

use Bwilliamson\Hooks\Model\ResourceModel\Hook\CollectionFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Bwilliamson_Hooks::manage_hooks_delete';

    public function __construct(
        Context           $context,
        private readonly CollectionFactory $collectionFactory,
        private readonly Filter $filter
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->collectionFactory->create();
        $items = $this->filter->getCollection($collection);
        $itemCount = $items->getSize();
        try {
            foreach ($items as $item) {
                $item->delete();
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 hooks(s) have been deleted.', $itemCount));
        } catch (NoSuchEntityException) {
            $this->messageManager->addErrorMessage(__('One or more hooks were not found.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (Exception) {
            $this->messageManager->addErrorMessage(__('Something went wrong while deleting hooks.'));
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}
