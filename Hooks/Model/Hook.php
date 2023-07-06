<?php declare(strict_types=1);

namespace Bwilliamson\Hooks\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\SerializerInterface;

class Hook extends AbstractModel
{
    const CACHE_TAG = 'bwilliamson_webhook_hook';
    protected $_cacheTag = 'bwilliamson_webhook_hook';
    protected $_eventPrefix = 'bwilliamson_webhook_hook';
    protected SerializerInterface $jsonSerializer;

    public function __construct(
        Context $context,
        Registry $registry,
        ResourceModel\Hook $resource,
        ResourceModel\Hook\Collection $resourceCollection,
        SerializerInterface $jsonSerializer,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->jsonSerializer = $jsonSerializer;
    }

    protected function _construct(): void
    {
        $this->_init(ResourceModel\Hook::class);
    }

    protected function _afterLoad(): Hook
    {
        if (!is_array($this->getHeaders())) {
            $value = $this->getHeaders();
            $this->setHeaders(empty($value) ? false : $this->jsonSerializer->unserialize($value));
        }

        return parent::_afterLoad();
    }
}
