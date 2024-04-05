define([], function () {
    'use strict';

    var createElement = function (tagName, attributes = {}) {
        var element = document.createElement(tagName);
        var keys = Object.keys(attributes);
        for (var key of keys) {
            element.setAttribute(key, attributes[key]);
        }
        return element;
    };

    var create = function (obj) {
        var keys = Object.keys(obj);
        var tagName = obj.tagName;
        var elem;
        if (keys.indexOf('attributes') === -1) {
            elem = createElement(tagName);
        } else {
            elem = createElement(tagName, obj['attributes']);
        }
        if (keys.indexOf('children') !== -1) {
            var children = obj['children'];
            for (var child of children) {
                var childElem = create(child);
                elem.appendChild(childElem);
            }
        }
        if (keys.indexOf('innerText') !== -1) {
            elem.innerText = obj['innerText'];
        }
        return elem;
    }

    return (mainObject) => create(mainObject) ;
});