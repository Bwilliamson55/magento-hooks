<?php declare(strict_types=1);

namespace Bwilliamson\Hooks\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Hook extends AbstractDb
{
    /** @var string Main table name */
    const MAIN_TABLE = 'bwilliamson_hooks_hook';

    /** @var string Main table primary key field name */
    const ID_FIELD_NAME = 'hook_id';

    /**
     * @param DateTime $date
     * @param Context $context
     */
    public function __construct(
        public DateTime $date,
        public Context  $context
    )
    {
        parent::__construct($this->context);
    }

    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }

    protected function _beforeSave(AbstractModel $object): Hook
    {
        //set default Update At and Create At time post
        $object->setUpdatedAt($this->date->date());
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->date->date());
        }

        return $this;
    }
}
