<?php

namespace Bwilliamson\Hooks\Ui\Component\Listing\Columns;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    public function __construct(
        ContextInterface       $context,
        UiComponentFactory     $uiComponentFactory,
        protected UrlInterface $urlBuilder,
        array                  $components = [],
        array                  $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }
        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['id']) && !isset($item['hook_id'])) {
                continue;
            }
            // Log or Hook?
            //Hooks/view/adminhtml/ui_component/bwilliamson_hooks_logs_listing.xml
            //Hooks/view/adminhtml/ui_component/bwilliamson_hooks_managehooks_listing.xml
            $type = $this->getData('type');
            if ($type === 'hook') {
                $item[$this->getData('name')] = [
                    'edit' => [
                        'href' => $this->urlBuilder->getUrl('bwhooks/managehooks/edit', ['hook_id' => $item['hook_id']]),
                        'label' => __('Edit'),
                        'hidden' => false,
                    ],
                    'delete' => [
                        'href' => $this->urlBuilder->getUrl('bwhooks/managehooks/delete', [
                            'hook_id' => $item['hook_id'],
                        ]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete %1', $item['name']),
                            'message' => __('Are you sure you want to delete the "%1" hook?', $item['name']),
                        ],
                    ],
                ];
            } elseif ($type === 'hook_log') {
                $item[$this->getData('name')] = [
                    'view' => [
                        'href' => $this->urlBuilder->getUrl('bwhooks/logs/edit', ['id' => $item['id']]),
                        'label' => __('View'),
                        'hidden' => false,
                    ],
                    'replay' => [
                        'href' => $this->urlBuilder->getUrl('bwhooks/logs/replay', ['id' => $item['id']]),
                        'label' => __('Replay'),
                        'hidden' => false,
                    ]
                ];
            }
        }
        return $dataSource;
    }
}
