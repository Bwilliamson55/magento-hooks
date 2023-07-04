<?php declare(strict_types=1);

namespace Bwilliamson\Hooks\Model\ResourceModel\Hook;

use Bwilliamson\Hooks\Model\Hook;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'hook_id';
    protected $_eventPrefix = 'bwilliamson_hooks_hook_collection';

    protected $_eventObject = 'hook_collection';

    protected function _construct(): void
    {
        $this->_init(Hook::class, \Bwilliamson\Hooks\Model\ResourceModel\Hook::class);
    }
}
