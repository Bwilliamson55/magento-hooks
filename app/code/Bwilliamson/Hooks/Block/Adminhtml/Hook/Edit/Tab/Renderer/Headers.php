<?php

namespace Bwilliamson\Hooks\Block\Adminhtml\Hook\Edit\Tab\Renderer;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class Headers extends AbstractFieldArray
{
    protected $_template = 'Bwilliamson_Hooks::hook/headers.phtml';

    public function _construct()
    {
        $this->addColumn('name', ['label' => __('Name')]);
        $this->addColumn('value', ['label' => __('Value')]);

        $this->_addAfter = false;

        parent::_construct();
    }

    public function getAddButtonLabel(): string
    {
        return __('Add');
    }
}
