define([], function() {
    'use strict';

    return function(pathname) {

        var result = '';
        
        if (!pathname) return;
        
        var arr = pathname.split('/');

        var indexId = arr.findIndex((elem) => {
            return elem === 'id';
        });

        var id = arr[indexId+1];
        console.log({ id });
        return result;
    }
});