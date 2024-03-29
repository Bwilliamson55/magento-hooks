<?php declare(strict_types=1);

namespace Bwilliamson\Hooks\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class History extends AbstractDb
{
    /** @var string Main table name */
    const MAIN_TABLE = 'bwilliamson_hooks_history';

    /** @var string Main table primary key field name */
    const ID_FIELD_NAME = 'id';

    /**
     * Initialize resource model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }
}
