<?php

namespace Bwilliamson\Hooks\Ui\Component\Listing\Columns;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;


class Actions extends Column
{
    protected UrlInterface $urlBuilder;

    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface       $urlBuilder,
        array              $components = [],
        array              $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $actions = $this->getData('action_list');
                foreach ($actions as $key => $action) {
                    $params = $action['params'];
                    foreach ($params as $field => $param) {
                        $params[$field] = $item[$param];
                    }
                    $parameters = [];
                    if (isset($action['params']['id']) && isset($item[$action['params']['id']])) {
                        $parameters['id'] = $item[$action['params']['id']];
                    }
                    if (isset($action['params']['hook_id']) && isset($item[$action['params']['hook_id']])) {
                        $parameters['hook_id'] = $item[$action['params']['hook_id']];
                    }
                    $item[$this->getData('name')][$key] = [
                        'href' => $this->urlBuilder->getUrl($action['path'], $parameters),
                        'label' => $action['label'],
                        'hidden' => false,
                    ];
                }
            }
        }

        return $dataSource;
    }
}
