<?php

namespace Bwilliamson\Hooks\Block\Adminhtml\Hook\Edit\Tab;

use Bwilliamson\Hooks\Model\Config\Source\HookType;
use Bwilliamson\Hooks\Model\Hook;
use Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Config\Model\Config\Source\Enabledisable;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Sales\Model\Config\Source\Order\Status as OrderStatus;
use Magento\Store\Model\System\Store;

class General extends Generic implements TabInterface
{

    public function __construct(
        Context                 $context,
        Registry                $registry,
        FormFactory             $formFactory,
        protected Enabledisable $enableDisable,
        protected Store         $systemStore,
        protected OrderStatus   $orderStatus,
        array                   $data = []
    )
    {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    protected function _prepareForm(): General
    {
        /** @var Hook $hook */
        $hook = $this->_coreRegistry->registry('bwilliamson_hooks_hook');
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('hook_');
        $form->setFieldNameSuffix('hook');

        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('General Information'),
            'class' => 'fieldset-wide'
        ]);

        $fieldset->addField('name', 'text', [
            'name' => 'name',
            'label' => __('Name'),
            'title' => __('Name'),
            'required' => true
        ]);
        $fieldset->addField('hook_type', 'hidden', [
            'name' => 'hook_type',
            'value' => $this->_request->getParam('type') ?: HookType::ORDER
        ]);
        $fieldset->addField('status', 'select', [
            'name' => 'status',
            'label' => __('Status'),
            'title' => __('Status'),
            'values' => $this->enableDisable->toOptionArray()
        ]);

        if ($this->_request->getParam('type') === HookType::ORDER || $hook->getHookType() === HookType::ORDER) {
            $fieldset->addField('order_status', 'multiselect', [
                'name' => 'order_status',
                'label' => __('Order Status'),
                'title' => __('Order Status'),
                'values' => $this->orderStatus->toOptionArray()
            ]);
        }

        if (!$this->_storeManager->isSingleStoreMode()) {
            /** @var RendererInterface $rendererBlock */
            $rendererBlock = $this->getLayout()->createBlock(Element::class);
            $fieldset->addField('store_ids', 'multiselect', [
                'name' => 'store_ids',
                'label' => __('Store Views'),
                'title' => __('Store Views'),
                'required' => true,
                'values' => $this->systemStore->getStoreValuesForForm(false, true)
            ])->setRenderer($rendererBlock);
        } else {
            $fieldset->addField('store_ids', 'hidden', [
                'name' => 'store_ids',
                'value' => $this->_storeManager->getStore()->getId()
            ]);
        }

        $fieldset->addField('priority', 'text', [
            'name' => 'priority',
            'label' => __('Priority'),
            'title' => __('Priority'),
            'note' => __('0 is highest')
        ]);

        $form->addValues($hook->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel(): string
    {
        return __('General');
    }

    public function getTabTitle(): string
    {
        return $this->getTabLabel();
    }

    public function canShowTab(): bool
    {
        return true;
    }

    public function isHidden(): bool
    {
        return false;
    }
}
