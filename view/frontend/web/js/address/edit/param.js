define([
    'jquery',
    'mage/translate'
], function ($, $t) {
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
                            innerText: $t('Comment')
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
                                class: 'input text comment',
                                maxlength: '255',
                                type: 'text',
                                name: 'comment',
                                title: $t('Shipping comment'),
                            }
                        }
                    ]
                }
            ]
        }
    }
});