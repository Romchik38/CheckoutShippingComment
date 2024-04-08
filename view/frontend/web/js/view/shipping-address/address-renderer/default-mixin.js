/*
area - storefront
url - checkout/index/index
replace address template to add Edit address link
*/
define(['mage/url'], function(url) {
    'use strict';

    return origFile => {

        origFile.defaults.template = 'Romchik38_CheckoutShippingComment/shipping-address/address-renderer/default';
        origFile.defaults.url = url.build(('customer/address/edit/id/'));

        return origFile;
    };
});