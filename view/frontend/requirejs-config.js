var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'Romchik38_CheckoutShippingComment/js/action/set-shipping-information-mixin': true
            },
            'Magento_Customer/js/model/customer-addresses': {
                'Romchik38_CheckoutShippingComment/js/model/customer-addresses-mixin': true
            },

            'Magento_Checkout/js/view/shipping-address/address-renderer/default': {
                'Romchik38_CheckoutShippingComment/js/view/shipping-address/address-renderer/default-mixin': true
            },

            
        }
    }
};
