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

    var commentId = getCommentId( window.location.pathname );
    if (commentId.length > 0) {
        // do request on server as fast as possible        
    }

    // main programm
    var execute = () => {
        if (commentId.length === 0) return;
        var tag = document.querySelector(param.selector);
        if (tag === null) return;
        var div = createInputTag(param.input);
        insertAfter(tag, div);
    };
    
    return function () {
        $('document').ready(execute);
    };
});