<?php

namespace Bwilliamson\Hooks\Block\Adminhtml\Hook;

use Bwilliamson\Hooks\Model\Hook;
use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;

class Edit extends Container
{
    protected $_objectId = 'hook_id';

    public function __construct(
        protected Registry $coreRegistry,
        Context            $context,
        array              $data = []
    )
    {
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_blockGroup = 'Bwilliamson_Hooks';
        $this->_controller = 'adminhtml_hook';

        parent::_construct();

        $this->buttonList->add('save-and-continue', [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => [
                        'event' => 'saveAndContinueEdit',
                        'target' => '#edit_form'
                    ]
                ]
            ]
        ], -100);
    }

    public function getFormActionUrl(): string
    {
        /** @var Hook $hook */
        $hook = $this->coreRegistry->registry('bwilliamson_hooks_hook');
        if ($id = $hook->getId()) {
            return $this->getUrl('*/*/save', ['hook_id' => $id]);
        }

        return parent::getFormActionUrl();
    }
}
