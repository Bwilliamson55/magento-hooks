define([
    'Magento_Ui/js/form/element/select',
    'uiRegistry'
], function (Select, registry) {
    'use strict';

    return Select.extend({
        initialize: function () {
            this._super();
            this.toggleOrderStatusField();
        },

        toggleOrderStatusField: function () {
            var orderStatusField = registry.get(this.parentName + '.order_status');
            if (this.value() === 'order') {
                orderStatusField.visible(true);
            } else {
                orderStatusField.visible(false);
            }
        },

        onUpdate: function (newValue) {
            this._super();
            this.toggleOrderStatusField();
        }
    });
});
