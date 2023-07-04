<?php

namespace Bwilliamson\Hooks\Ui\Component\Listing\Columns;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface as StoreManager;
use Magento\Store\Model\System\Store as SystemStore;
use Magento\Ui\Component\Listing\Columns\Column;

class Store extends Column
{
    protected StoreManager $storeManager;
    protected string $storeKey;

    public function __construct(
        ContextInterface      $context,
        UiComponentFactory    $uiComponentFactory,
        protected SystemStore $systemStore,
        protected Escaper     $escaper,
        StoreManager          $storeManager,
        array                 $components = [],
        array                 $data = [],
                              $storeKey = 'store_ids'
    )
    {
        $this->storeKey = $storeKey;
        $this->storeManager = $storeManager;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = $this->prepareItem($item);
            }
        }

        return $dataSource;
    }

    protected function prepareItem(array $item): string
    {
        $content = '';
        $origStores = explode(',', $item[$this->storeKey]);
        if (!is_array($origStores)) {
            $origStores = [$origStores];
        }
        if (in_array(0, $origStores)) {
            return __('All Store Views');
        }
        $data = $this->systemStore->getStoresStructure(false, $origStores);

        foreach ($data as $website) {
            $content .= "<b>" . $website['label'] . "</b><br/>";
            foreach ($website['children'] as $group) {
                $content .= str_repeat('&nbsp;', 3) . "<b>" . $this->escaper->escapeHtml($group['label']) . "</b><br/>";
                foreach ($group['children'] as $store) {
                    $content .= str_repeat('&nbsp;', 6) . $this->escaper->escapeHtml($store['label']) . "<br/>";
                }
            }
        }

        return $content;
    }

    public function prepare()
    {
        parent::prepare();
        if ($this->getStoreManager()->isSingleStoreMode()) {
            $this->_data['config']['componentDisabled'] = true;
        }
    }

    private function getStoreManager()
    {
        $this->storeManager = ObjectManager::getInstance()
            ->get(StoreManager::class);

        return $this->storeManager;
    }
}
