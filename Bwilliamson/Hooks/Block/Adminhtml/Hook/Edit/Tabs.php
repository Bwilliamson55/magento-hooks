<?php

namespace Bwilliamson\Hooks\Block\Adminhtml\Hook\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{

    protected function _construct(): void
    {
        parent::_construct();

        $this->setId('hook_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('HOOK INFO'));
    }
}
