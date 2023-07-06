<?php

namespace Bwilliamson\Hooks\Model\Repository;

use Bwilliamson\Hooks\Model\Hook;
use Bwilliamson\Hooks\Model\ResourceModel\Hook as HookResourceModel;
use Bwilliamson\Hooks\Model\ResourceModel\Hook\CollectionFactory;
use Exception;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class HooksRepository
{

    /**
     * @param CollectionFactory $hooksCollectionFactory
     * @param HookResourceModel $hookResourceModel
     */
    public function __construct(
        private readonly CollectionFactory $hooksCollectionFactory,
        private readonly HookResourceModel $hookResourceModel
    ) {
    }

    /**
     * Retrieve a hook by its ID.
     *
     * @param int $hookId
     * @return Hook|DataObject
     * @throws NoSuchEntityException
     */
    public function getById(int $hookId): Hook|DataObject
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
     * Retrieve a list of hooks based on filters.
     *
     * @param array $filters
     * @param int|null $pageSize
     * @return Hook[]
     */
    public function getList(array $filters = [], ?int $pageSize = null): array
    {
        $collection = $this->hooksCollectionFactory->create();
        foreach ($filters as $field => $value) {
            $collection->addFieldToFilter($field, $value);
        }
        if ($pageSize !== null) {
            $collection->setPageSize($pageSize);
        }

        return $collection->getItems();
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
}
