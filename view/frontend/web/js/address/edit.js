define([
    'jquery',
    './edit/param',
    './edit/input',
    './edit/url'
], function ($, param, createInputTag, getCommentId) {
    'use strict';

    var insertAfter = function (referenceNode, newNode) {
        referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
    }

    // do request on server as fast as possible
    var url = getCommentId( window.location.pathname );


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