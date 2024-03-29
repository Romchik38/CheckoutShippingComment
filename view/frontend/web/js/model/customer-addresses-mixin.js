define([], function() {
    'use strict';

    return origFile => {

        var items = origFile.getAddressItems();

        for (var item of items) {
            item.customAttributes.push(
                { 
                    attribute_code: "comment_field", 
                    value: "asdf" 
                }
            );
        }

        return {
            getAddressItems: function(){
                return items;
            }
        };
    };
});