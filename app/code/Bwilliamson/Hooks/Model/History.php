<?php declare(strict_types=1);

namespace Bwilliamson\Hooks\Model;

use Magento\Framework\Model\AbstractModel;

class History extends AbstractModel
{
    const CACHE_TAG = 'bwilliamson_webhook_history';
    protected $_cacheTag = 'bwilliamson_webhook_history';
    protected $_eventPrefix = 'bwilliamson_webhook_history';

    protected function _construct(): void
    {
        $this->_init(ResourceModel\History::class);
    }
}
