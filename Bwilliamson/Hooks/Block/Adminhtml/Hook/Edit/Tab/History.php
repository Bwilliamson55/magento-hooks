<?php

namespace Bwilliamson\Hooks\Block\Adminhtml\Hook\Edit\Tab;

use Bwilliamson\Hooks\Model\Config\Source\Status;
use Bwilliamson\Hooks\Model\Hook;
use Bwilliamson\Hooks\Model\ResourceModel\History\CollectionFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Helper\Data;
use Magento\Framework\Registry;

class History extends Extended implements TabInterface
{

    protected Status $_status;

    public function __construct(
        Context                     $context,
        protected Registry          $coreRegistry,
        Data                        $backendHelper,
        protected CollectionFactory $historyCollectionFactory,
        Status                      $status,
        array                       $data = []
    )
    {
        $this->_status = $status;
        parent::__construct($context, $backendHelper, $data);
    }

    public function _construct()
    {
        parent::_construct();

        $this->setId('hook_history_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(false);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection(): History
    {
        $hook = $this->getHook();
        $collection = $this->historyCollectionFactory->create();
        $collection = $collection->addFieldToFilter('hook_id', $hook->getId());
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns(): static
    {
        $this->addColumn('id', [
            'header' => __('Log ID'),
            'sortable' => true,
            'index' => 'id',
            'type' => 'number',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
        ]);
        $this->addColumn('hook_name', [
            'header' => __('Hook Name'),
            'name' => 'hook_name',
            'index' => 'hook_name'
        ]);
        $this->addColumn('status', [
            'header' => __('Status'),
            'name' => 'status',
            'index' => 'status',
            'type' => 'options',
            'sortable' => false,
            'options' => $this->_status->toArray(),
            'header_css_class' => 'col-status',
            'column_css_class' => 'col-status'
        ]);
        $this->addColumn('hook_type', [
            'header' => __('Entity'),
            'name' => 'hook_type',
            'index' => 'hook_type'
        ]);
        $this->addColumn('message', [
            'header' => __('Message'),
            'name' => 'message',
            'index' => 'message'
        ]);

        return $this;
    }

    public function getGridUrl(): string
    {
        return $this->getUrl('*/*/log', ['id' => $this->getHook()->getId()]);
    }

    /**
     * @return Hook
     */
    public function getHook(): Hook
    {
        return $this->coreRegistry->registry('bwilliamson_hooks_hook');
    }

    public function getTabLabel(): string
    {
        return __('Logs');
    }

    public function isHidden(): bool
    {
        return false;
    }

    public function getTabTitle(): string
    {
        return $this->getTabLabel();
    }

    public function canShowTab(): bool
    {
        return true;
    }

    public function getTabUrl(): string
    {
        return $this->getUrl('bwhooks/logs/log', ['_current' => true]);
    }

    public function getTabClass(): string
    {
        return 'ajax only';
    }
}
