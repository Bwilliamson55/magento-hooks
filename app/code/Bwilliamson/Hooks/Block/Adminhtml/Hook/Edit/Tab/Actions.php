<?php

namespace Bwilliamson\Hooks\Block\Adminhtml\Hook\Edit\Tab;

use Bwilliamson\Hooks\Block\Adminhtml\Hook\Edit\Tab\Renderer\Body;
use Bwilliamson\Hooks\Block\Adminhtml\Hook\Edit\Tab\Renderer\Headers;
use Bwilliamson\Hooks\Model\Config\Source\Authentication;
use Bwilliamson\Hooks\Model\Config\Source\ContentType;
use Bwilliamson\Hooks\Model\Config\Source\Method;
use Bwilliamson\Hooks\Model\Hook;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Element\Dependence;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;


class Actions extends Generic implements TabInterface
{

    public function __construct(
        Context                  $context,
        Registry                 $registry,
        FormFactory              $formFactory,
        protected FieldFactory   $fieldFactory,
        protected Method         $method,
        protected ContentType    $contentType,
        protected Authentication $authentication,
        array                    $data = []
    )
    {

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @throws LocalizedException
     */
    protected function _prepareForm(): Actions
    {
        /** @var Hook $rule */
        $hook = $this->_coreRegistry->registry('bwilliamson_hooks_hook');

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('hook_');
        $form->setFieldNameSuffix('hook');

        $fieldset = $form->addFieldset('actions_fieldset', [
            'legend' => __('Actions'),
            'class' => 'fieldset-wide'
        ]);
        $fieldset->addField('payload_url', 'text', [
            'name' => 'payload_url',
            'label' => __('Payload URL'),
            'title' => __('Payload URL'),
            'required' => true,
            'note' => __('You can insert a variable'),
            'after_element_html' => '<a id="insert-variable-upload" class="btn">' . __('Insert Variable') . '</a>',
        ]);
        $fieldset->addField('method', 'select', [
            'name' => 'method',
            'label' => __('Method'),
            'title' => __('Method'),
            'values' => $this->method->toOptionArray(),
        ]);

        $authentication = $fieldset->addField('authentication', 'select', [
            'name' => 'authentication',
            'label' => __('Authentication'),
            'title' => __('Authentication'),
            'values' => $this->authentication->toOptionArray(),

        ]);
        $username = $fieldset->addField('username', 'text', [
            'name' => 'username',
            'label' => __('Username'),
            'title' => __('Username'),
        ]);
        $password = $fieldset->addField('password', 'password', [
            'name' => 'password',
            'label' => __('Password'),
            'title' => __('Password'),
        ]);
        /** @var RendererInterface $rendererBlock */
        $rendererBlock = $this->getLayout()
            ->createBlock(Headers::class);
        $fieldset->addField('headers', 'text', [
            'name' => 'headers',
            'label' => __('Header'),
            'title' => __('Header'),
        ])->setRenderer($rendererBlock);
        $fieldset->addField('content_type', 'select', [
            'name' => 'content_type',
            'label' => __('Content Type'),
            'title' => __('Content Type'),
            'values' => $this->contentType->toOptionArray(),

        ]);
        /** @var RendererInterface $rendererBlock */
        $rendererBlock = $this->getLayout()->createBlock(Body::class);
        $fieldset->addField('body', 'textarea', [
            'name' => 'body',
            'label' => __('Body'),
            'title' => __('Body'),
            'note' => __(
                'Supports <a href="%1" target="_blank">Liquid template</a>',
                'https://shopify.github.io/liquid/'
            )
        ])->setRenderer($rendererBlock);

        $refField = $this->fieldFactory->create([
            'fieldData' => ['value' => 'basic,digest', 'separator' => ','],
            'fieldPrefix' => ''
        ]);

        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock(Dependence::class)
                ->addFieldMap($authentication->getHtmlId(), $authentication->getName())
                ->addFieldMap($username->getHtmlId(), $username->getName())
                ->addFieldMap($password->getHtmlId(), $password->getName())
                ->addFieldDependence($username->getName(), $authentication->getName(), $refField)
                ->addFieldDependence($password->getName(), $authentication->getName(), $refField)
        );

        $form->addValues($hook->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel(): string
    {
        return __('Actions');
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

    public function getFormHtml(): string
    {
        $formHtml = parent::getFormHtml();
        $childHtml = $this->getChildHtml();

        return $formHtml . $childHtml;
    }
}
