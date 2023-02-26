<?php

namespace Bwilliamson\Hooks\Block\Adminhtml\Logs\Edit;

use Bwilliamson\Hooks\Model\Config\Source\HookType;
use Bwilliamson\Hooks\Model\Config\Source\Status;
use Bwilliamson\Hooks\Model\History;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;

class Form extends Generic
{

    protected HookType $hookType;

    public function __construct(
        Context     $context,
        Registry    $registry,
        FormFactory $formFactory,
        HookType    $hookType,
        array       $data = []
    )
    {
        $this->hookType = $hookType;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @throws LocalizedException
     */
    protected function _prepareForm(): Form
    {
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ]
        ]);
        $form->setUseContainer(true);
        /** @var History $log */
        $log = $this->_coreRegistry->registry('bwilliamson_hooks_log');

        $log->setStatus((int)$log->getStatus() === Status::SUCCESS ? __('Success') : __('Error'));

        $form->setHtmlIdPrefix('log_');
        $form->setFieldNameSuffix('log');

        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('General Information'),
            'class' => 'fieldset-wide'
        ]);

        $fieldset->addField('id', 'label', [
            'name' => 'id',
            'label' => __('Log ID'),
            'title' => __('Log ID'),
        ]);
        $fieldset->addField('hook_type', 'label', [
            'name' => 'hook_type',
            'label' => __('Entity'),
            'title' => __('Entity'),
            'values' => $this->hookType->toOptionArray()
        ]);
        $fieldset->addField('status', 'label', [
            'name' => 'status',
            'label' => __('Status'),
            'title' => __('Status'),
        ]);

        $fieldset->addField('response', 'textarea', [
            'name' => 'response',
            'label' => __('Response'),
            'title' => __('Response'),
            'readonly' => true
        ]);

        $fieldset->addField('body', 'textarea', [
            'name' => 'body',
            'label' => __('Request Body'),
            'title' => __('Request Body')
        ]);

        $form->addValues($log->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
