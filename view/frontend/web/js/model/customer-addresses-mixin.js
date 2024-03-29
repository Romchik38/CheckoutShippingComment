define([], function() {
    'use strict';

    return origFile => {

        var items = origFile.getAddressItems();
        var addresses = window.customerData.addresses;

        for (var item of items) {
            var address = addresses[item.customerAddressId];
            if (!address || typeof address !== 'object') continue;
            var keys = Object.keys(address);
            if (keys.indexOf('extension_attributes') === -1) continue;

            var extensionAttributes = address['extension_attributes'];
            var attributeCodes = Object.keys(extensionAttributes);
            
            for (var attrinuteCode of attributeCodes) {
                item.customAttributes.push(
                    { 
                        attribute_code: attrinuteCode, 
                        value: extensionAttributes[attrinuteCode]
                    }
                );
    
            }
        }

        return {
            getAddressItems: function(){
                return items;
            }
        };
    };
});