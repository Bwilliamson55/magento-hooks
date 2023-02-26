<?php

namespace Bwilliamson\Hooks\Model\ResourceModel\History;

use Bwilliamson\Hooks\Model\ResourceModel\History;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'bwilliamson_hooks_history_collection';
    protected $_eventObject = 'history_collection';

    protected function _construct()
    {
        $this->_init(\Bwilliamson\Hooks\Model\History::class, History::class);
    }
}
