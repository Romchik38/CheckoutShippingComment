define([], function () {
    'use strict';
    return {
        selector: '.field.zip.required',
        input: {
            tagName: 'div',
            attributes: { class: 'field' },
            children: [
                {
                    tagName: 'label',
                    attributes: { class: 'label' },
                    children: [
                        {
                            tagName: 'span',
                            innerText: 'Comment'
                        }
                    ]

                },
                {
                    tagName: 'div',
                    attributes: { class: 'control' },
                    children: [
                        {
                            tagName: 'input',
                            attributes: {
                                class: 'input text',
                                maxlength: '255',
                                type: 'text',
                                name: 'comment',
                                title: 'Shipping comment',
                                disabled: 0
                            }
                        }
                    ]
                }
            ]
        }
    }
});