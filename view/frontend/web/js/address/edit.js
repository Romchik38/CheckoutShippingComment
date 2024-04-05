define(['jquery'], function ($) {
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
        var zip = document.querySelector('.field.zip.required');
        if (zip === null) return;

        var div = document.createElement("div");
        div.setAttribute('class', 'field');

        var label = document.createElement('label');
        label.setAttribute('class', 'label');

        var labelSpan = document.createElement('span');
        labelSpan.innerText = 'Comment';

        var divControl = document.createElement('div');
        divControl.setAttribute('class', 'control');

        var input = document.createElement('input');
        input.setAttribute('class', 'input text');
        input.setAttribute('maxlength', '255');
        input.setAttribute('type', 'text');
        input.setAttribute('name', 'comment');
        input.setAttribute('title', 'Shipping comment');
        input.setAttribute('disabled', 1);

        div.appendChild(label);
        div.appendChild(divControl);
        divControl.appendChild(input);
        label.appendChild(labelSpan);

        insertAfter(zip, div);

    };
    return function () {
        $('document').ready(execute);
    };
});