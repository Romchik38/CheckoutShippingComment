define([], function() {
    'use strict';

    return origFile => {

        var template = 'Romchik38_CheckoutShippingComment/shipping-address/address-renderer/default';

        origFile.defaults.template = template;

        return origFile;
    };
});