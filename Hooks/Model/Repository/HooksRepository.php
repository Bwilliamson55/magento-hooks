<?php

namespace Bwilliamson\Hooks\Model\Repository;

use Bwilliamson\Hooks\Api\HooksRepositoryInterface;
use Bwilliamson\Hooks\Model\Hook;
use Bwilliamson\Hooks\Model\ResourceModel\Hook as HookResourceModel;
use Bwilliamson\Hooks\Model\ResourceModel\Hook\CollectionFactory;
use Exception;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

class HooksRepository implements HooksRepositoryInterface
{

    /**
     * @param CollectionFactory $hooksCollectionFactory
     * @param HookResourceModel $hookResourceModel
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        private readonly CollectionFactory $hooksCollectionFactory,
        private readonly HookResourceModel $hookResourceModel,
        private readonly SearchResultsInterfaceFactory $searchResultsFactory,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * Retrieve a hook by its ID.
     *
     * @param int $hookId
     * @return Hook
     * @throws NoSuchEntityException
     */
    public function getById(int $hookId): Hook
    {
        $hook = $this->hooksCollectionFactory->create()
            ->addFieldToFilter('hook_id', $hookId)
            ->setPageSize(1)
            ->getFirstItem();

        if (!$hook->getId()) {
            throw new NoSuchEntityException(__('Hook with ID "%1" does not exist.', $hookId));
        }

        return $hook;
    }

    /**
     * Retrieve a list of hooks based on search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->hooksCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $this->searchCriteriaBuilder->setCriteria($searchCriteria);
        $this->searchCriteriaBuilder->setRequestName('bwilliamson_hooks_hook_listing');
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * Save the hook to the database.
     *
     * @param Hook $hook
     * @return Hook
     * @throws CouldNotSaveException
     */
    public function save(Hook $hook): Hook
    {
        try {
            $this->hookResourceModel->save($hook);
        } catch (Exception $e) {
            throw new CouldNotSaveException(__('Could not save the hook: %1', $e->getMessage()), $e);
        }

        return $hook;
    }

    /**
     * Delete the hook from the database.
     *
     * @param Hook $hook
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Hook $hook): bool
    {
        try {
            $this->hookResourceModel->delete($hook);
        } catch (Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete the hook with ID %1', $hook->getId()), $e);
        }

        return true;
    }

    /**
     * Delete a hook by its ID.
     *
     * @param int $hookId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $hookId): bool
    {
        $hook = $this->getById($hookId);

        return $this->delete($hook);
    }

    /**
     * Delete multiple hooks by their IDs.
     *
     * @param array $hookIds
     * @return int The number of deleted hooks
     * @throws CouldNotDeleteException
     */
    public function deleteByIds(array $hookIds): int
    {
        $deletedHooks = 0;
        foreach ($hookIds as $hookId) {
            try {
                $this->deleteById($hookId);
                $deletedHooks++;
            } catch (NoSuchEntityException $e) {
                // Ignore if the hook does not exist
            }
        }

        return $deletedHooks;
    }
}
