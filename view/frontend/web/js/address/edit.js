define([
    'jquery',
    './edit/param',
    './edit/input',
    './edit/url',
    'mage/url'
], function ($, param, createInputTag, getCommentId, url) {
    'use strict';

    var insertAfter = function (referenceNode, newNode) {
        referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
    }

    var ready = {
        comment: undefined,
        tagReady: false,
        showComment: function () {
            if (this.comment !== undefined && this.tagReady) {
                $('.input.text.comment').val(this.comment);
            }
        },
        addComment: function (text) {
            this.comment = text;
            this.showComment();
        },
        tagAdded: function () {
            this.tagReady = true;
            this.showComment();
        }
    }

    var commentId = getCommentId(window.location.pathname);
    if (commentId.length > 0) {
        // do request on the server as fast as possible   
        var requestUrl = url.build(('shippingcomment/customer/edit/id/' + commentId));

        $.ajax({
            url: requestUrl,
        })
            .done(function (data) {
                if (typeof (data) !== 'object') {
                    return;
                }
                var keys = Object.keys(data);
                if (keys.indexOf('data') > -1) {
                    ready.addComment(data.data);
                }
            });
    }

    // main programm
    var execute = () => {
        var tag = document.querySelector(param.selector);
        if (tag === null) return;
        var div = createInputTag(param.input);
        insertAfter(tag, div);
        ready.tagAdded();
    };

    return function () {
        $('document').ready(execute);
    };
});