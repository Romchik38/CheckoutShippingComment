define([
    'jquery',
    './param',
    './input'
], function ($, param, createInputTag) {
    'use strict';

    var insertAfter = function (referenceNode, newNode) {
        referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
    }

    // do request on server as fast as possible

    // ....
    // ....
    // ....

    // main programm
    var execute = () => {
        var tag = document.querySelector(param.selector);
        if (tag === null) return;

        var div = createInputTag(param.input);

        insertAfter(tag, div);

    };
    
    return function () {
        $('document').ready(execute);
    };
});