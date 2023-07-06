<?php

namespace Bwilliamson\Hooks\Api;

use Bwilliamson\Hooks\Model\Hook;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface HooksRepositoryInterface
{
    /**
     * Retrieve a hook by its ID.
     *
     * @param int $hookId
     * @return Hook
     * @throws NoSuchEntityException
     */
    public function getById(int $hookId): Hook;

    /**
     * Retrieve a list of hooks based on search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;


    /**
     * Save the hook to the database.
     *
     * @param Hook $hook
     * @return Hook
     * @throws CouldNotSaveException
     */
    public function save(Hook $hook): Hook;

    /**
     * Delete the hook from the database.
     *
     * @param Hook $hook
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Hook $hook): bool;

    /**
     * Delete a hook by its ID.
     *
     * @param int $hookId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $hookId): bool;

    /**
     * Delete multiple hooks by ID.
     *
     * @param array $ids
     * @return int
     */
    public function deleteByIds(array $ids): int;
}
