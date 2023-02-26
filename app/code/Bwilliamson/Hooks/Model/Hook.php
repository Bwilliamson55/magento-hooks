<?php declare(strict_types=1);

namespace Bwilliamson\Hooks\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Serialize\Serializer\Json;

class Hook extends AbstractModel
{
    const CACHE_TAG = 'bwilliamson_webhook_hook';
    protected $_cacheTag = 'bwilliamson_webhook_hook';
    protected $_eventPrefix = 'bwilliamson_webhook_hook';
    protected Json $json;

    protected function _construct(): void
    {
        $this->_init(ResourceModel\Hook::class);
    }

    protected function _afterLoad(): Hook
    {
        if (!is_array($this->getHeaders())) {
            $value = $this->getHeaders();
            $this->setHeaders(empty($value) ? false : (new Json())->unserialize($value));
        }

        return parent::_afterLoad();
    }
}
