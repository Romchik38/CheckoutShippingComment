var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'Romchik38_CheckoutShippingComment/js/action/set-shipping-information-mixin': true
            },
            //in progress
            'Magento_Customer/js/model/customer-addresses': {
                'Romchik38_CheckoutShippingComment/js/model/customer-addresses-mixin': true
            }
        }
    }
};
