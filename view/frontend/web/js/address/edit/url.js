define([
], function(
) {
    'use strict';

    return function(pathname) {

        var result = '';
        
        if (!pathname) return;
        
        var arr = pathname.split('/');

        var indexId = arr.findIndex((elem) => {
            return elem === 'id';
        });

        if (indexId === -1) return result;

        if ((arr.length + 1) === indexId) return result;

        result = arr[indexId+1];

        return result;
    }
});