<?php

namespace Bwilliamson\Hooks\Block\Adminhtml\Hook\Edit\Tab\Renderer;

use Bwilliamson\Hooks\Block\Adminhtml\LiquidFilters;
use Bwilliamson\Hooks\Model\Config\Source\HookType;
use Bwilliamson\Hooks\Model\HookFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as CatalogEavAttr;
use Magento\Customer\Model\ResourceModel\Customer as CustomerResource;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Newsletter\Model\ResourceModel\Subscriber;
use Magento\Quote\Model\ResourceModel\Quote;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\ResourceModel\Order\Address;
use Magento\Sales\Model\ResourceModel\Order\Creditmemo as CreditmemoResource;
use Magento\Sales\Model\ResourceModel\Order\Invoice as InvoiceResource;
use Magento\Sales\Model\ResourceModel\Order\Shipment as ShipmentResource;
use Magento\Sales\Model\ResourceModel\Order\Status\History as OrderStatusResource;

class Body extends Element
{

    protected $_template = 'Bwilliamson_Hooks::hook/body.phtml';

    public function __construct(
        Context                       $context,
        protected OrderFactory        $orderFactory,
        protected InvoiceResource     $invoiceResource,
        protected ShipmentResource    $shipmentResource,
        protected CreditmemoResource  $creditmemoResource,
        protected OrderStatusResource $orderStatusResource,
        protected CustomerResource    $customerResource,
        protected Quote               $quoteResource,
        protected CatalogEavAttr      $catalogEavAttribute,
        protected CategoryFactory     $categoryFactory,
        protected LiquidFilters       $liquidFilters,
        protected HookFactory         $hookFactory,
        protected Subscriber          $subscriber,
        protected Address             $addressResource,
        array                         $data = []
    )
    {
        parent::__construct($context, $data);
    }

    public function render(AbstractElement $element): string
    {
        $this->_element = $element;

        return $this->toHtml();
    }

    public function getHookType()
    {
        $type = $this->_request->getParam('type');
        if (!$type) {
            $hookId = $this->getRequest()->getParam('hook_id');
            $hook = $this->hookFactory->create()->load($hookId);
            $type = $hook->getHookType();
        }
        if (!$type) {
            $type = 'order';
        }

        return $type;
    }

    /**
     * @throws LocalizedException
     */
    public function getHookAttrCollection(): array
    {
        $hookType = $this->getHookType();

        switch ($hookType) {
            case HookType::NEW_ORDER_COMMENT:
                $collectionData = $this->orderStatusResource->getConnection()
                    ->describeTable($this->orderStatusResource->getMainTable());
                $attrCollection = $this->getAttrCollectionFromDb($collectionData);
                break;
            case HookType::NEW_INVOICE:
                $collectionData = $this->invoiceResource->getConnection()
                    ->describeTable($this->invoiceResource->getMainTable());
                $attrCollection = $this->getAttrCollectionFromDb($collectionData);
                break;
            case HookType::NEW_SHIPMENT:
                $collectionData = $this->shipmentResource->getConnection()
                    ->describeTable($this->shipmentResource->getMainTable());
                $attrCollection = $this->getAttrCollectionFromDb($collectionData);
                break;
            case HookType::NEW_CREDITMEMO:
                $collectionData = $this->creditmemoResource->getConnection()
                    ->describeTable($this->creditmemoResource->getMainTable());
                $attrCollection = $this->getAttrCollectionFromDb($collectionData);
                break;
            case HookType::NEW_CUSTOMER:
            case HookType::UPDATE_CUSTOMER:
            case HookType::DELETE_CUSTOMER:
            case HookType::CUSTOMER_LOGIN:
                $collectionData = $this->customerResource->loadAllAttributes()->getSortedAttributes();
                $attrCollection = $this->getAttrCollectionFromEav($collectionData);
                break;
            case HookType::NEW_PRODUCT:
            case HookType::UPDATE_PRODUCT:
            case HookType::DELETE_PRODUCT:
                $collectionData = $this->catalogEavAttribute->getCollection()
                    ->addFieldToFilter(AttributeSet::KEY_ENTITY_TYPE_ID, 4);
                $attrCollection = $this->getAttrCollectionFromEav($collectionData);
                break;
            case HookType::NEW_CATEGORY:
            case HookType::UPDATE_CATEGORY:
            case HookType::DELETE_CATEGORY:
                $collectionData = $this->categoryFactory->create()->getAttributes();
                $attrCollection = $this->getAttrCollectionFromEav($collectionData);
                break;
            case HookType::ABANDONED_CART:
                $collectionData = $this->quoteResource->getConnection()
                    ->describeTable($this->quoteResource->getMainTable());
                $attrCollection = $this->getAttrCollectionFromDb($collectionData);
                break;
            case HookType::SUBSCRIBER:
                $collectionData = $this->subscriber->getConnection()
                    ->describeTable($this->subscriber->getMainTable());
                $attrCollection = $this->getAttrCollectionFromDb($collectionData);
                break;
            default:
                $collectionData = $this->orderFactory->create()->getResource()->getConnection()
                    ->describeTable($this->orderFactory->create()->getResource()->getMainTable());
                $attrCollection = $this->getAttrCollectionFromDb($collectionData);
                break;
        }

        return $attrCollection;
    }

    protected function getAttrCollectionFromDb($collection): array
    {
        $attrCollection = [];
        foreach ($collection as $item) {
            $attrCollection[] = new DataObject([
                'name' => $item['COLUMN_NAME'],
                'title' => ucwords(str_replace('_', ' ', $item['COLUMN_NAME']))
            ]);
        }

        return $attrCollection;
    }

    protected function getAttrCollectionFromEav($collection): array
    {
        $attrCollection = [];
        foreach ($collection as $item) {
            $attrCollection[] = new DataObject([
                'name' => $item->getAttributeCode(),
                'title' => $item->getDefaultFrontendLabel()
            ]);
        }

        return $attrCollection;
    }

    public function getModifier(): array
    {
        return $this->liquidFilters->getFilters();
    }

    public function getShippingAddressAttrCollection(): array
    {
        $collectionData = $this->addressResource->getConnection()
            ->describeTable($this->addressResource->getMainTable());
        return $this->getAttrCollectionFromDb($collectionData);
    }
}
