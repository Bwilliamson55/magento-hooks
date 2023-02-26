<?php

namespace Bwilliamson\Hooks\Block\Adminhtml;

use Bwilliamson\Hooks\Model\Config\Source\HookType;
use Magento\Backend\Block\Widget\Button\SplitButton;
use Magento\Backend\Block\Widget\Container;
use Magento\Backend\Block\Widget\Context;

class ManageHooks extends Container
{
    protected HookType $hookType;

    public function __construct(
        Context  $context,
        HookType $hookType,
        array    $data = []
    )
    {
        parent::__construct($context, $data);

        $this->hookType = $hookType;
    }

    protected function _prepareLayout(): ManageHooks
    {
        $addButtonProps = [
            'id' => 'add_new_hook',
            'label' => __('Add New'),
            'class' => 'add',
            'button_class' => '',
            'class_name' => SplitButton::class,
            'options' => $this->_getAddProductButtonOptions(),
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        return parent::_prepareLayout();
    }

    protected function _getAddProductButtonOptions(): array
    {
        $splitButtonOptions = [];

        foreach ($this->hookType->toOptionArray() as $hookType) {
            $splitButtonOptions[$hookType['value']] = [
                'label' => $hookType['label'],
                'onclick' => "setLocation('" . $this->getUrl('bwhooks/managehooks/new', [
                        'type' => $hookType['value']
                    ]) . "')",
                'default' => $hookType['value'] === 'order',
            ];
        }

        return $splitButtonOptions;
    }
}
