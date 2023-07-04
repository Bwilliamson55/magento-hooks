<?php

namespace Bwilliamson\Hooks\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class HookType implements OptionSourceInterface
{
    public const ORDER = 'order';
    public const NEW_ORDER_COMMENT = 'new_order_comment';
    public const NEW_INVOICE = 'new_invoice';
    public const NEW_SHIPMENT = 'new_shipment';
    public const NEW_CREDITMEMO = 'new_creditmemo';
    public const NEW_CUSTOMER = 'new_customer';
    public const UPDATE_CUSTOMER = 'update_customer';
    public const DELETE_CUSTOMER = 'delete_customer';
    public const NEW_PRODUCT = 'new_product';
    public const UPDATE_PRODUCT = 'update_product';
    public const DELETE_PRODUCT = 'delete_product';
    public const NEW_CATEGORY = 'new_category';
    public const UPDATE_CATEGORY = 'update_category';
    public const DELETE_CATEGORY = 'delete_category';
    public const CUSTOMER_LOGIN = 'customer_login';
    public const SUBSCRIBER = 'subscriber';
    public const ABANDONED_CART = 'abandoned_cart';

    /**
     * Returns an array of options for the dropdown/select field.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [];
        foreach ($this->toArray() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $options;
    }

    /**
     * Returns an array of hook types.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::ORDER => 'Order',
            self::NEW_ORDER_COMMENT => 'New Order Comment',
            self::NEW_INVOICE => 'New Invoice',
            self::NEW_SHIPMENT => 'New Shipment',
            self::NEW_CREDITMEMO => 'New Credit Memo',
            self::NEW_CUSTOMER => 'New Customer',
            self::UPDATE_CUSTOMER => 'Update Customer',
            self::DELETE_CUSTOMER => 'Delete Customer',
            self::NEW_PRODUCT => 'New Product',
            self::UPDATE_PRODUCT => 'Update Product',
            self::DELETE_PRODUCT => 'Delete Product',
            self::NEW_CATEGORY => 'New Category',
            self::UPDATE_CATEGORY => 'Update Category',
            self::DELETE_CATEGORY => 'Delete Category',
            self::CUSTOMER_LOGIN => 'Customer Login',
            self::SUBSCRIBER => 'Subscriber',
            self::ABANDONED_CART => 'Abandoned Cart',
        ];
    }
}
