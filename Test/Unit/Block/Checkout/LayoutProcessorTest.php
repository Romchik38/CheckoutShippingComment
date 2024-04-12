<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Block\Checkout;

use Romchik38\CheckoutShippingComment\Block\Checkout\LayoutProcessor;
use Magento\Framework\App\Config;
use Magento\Framework\Phrase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LayoutProcessorTest extends TestCase
{
    private $commentField;
    private $sortOrder = 100;
    private $jsLayout;

    protected function setUp(): void
    {
        $this->commentField = [

            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/textarea',
                'cols' => '20',
                'rows' => '2',
                'tooltip' => [
                    'description' => __('add additional information to delivery')->getText(),
                ]
            ],
            'dataScope' => 'shippingAddress.custom_attributes.comment_field',
            'label' => __('Shipping Comment')->getText(),  // Phrase
            'provider' => 'checkoutProvider',
            'sortOrder' => $this->sortOrder,
            'validation' => [
                'required-entry' => false,
                'max_text_length' => 255
            ],
            'options' => [],
            'filterBy' => null,
            'customEntry' => null,
            'visible' => true,
            'value' => '' // value field is used to set a default value of the attribute

        ];

        $this->jsLayout = [
            'components' => [
                'checkout' => [
                    'children' => [
                        'steps' => [
                            'children' => [
                                'shipping-step' => [
                                    'children' => [
                                        'shippingAddress' => [
                                            'children' => [
                                                'shipping-address-fieldset' => [
                                                    'children' => []
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function testProcess()
    {
        $scopeConfig = $this->createMock(Config::class);
        $scopeConfig->method('getValue')->willReturn($this->sortOrder);

        $phrase = $this->createMock(Phrase::class);

        $layoutProcessor = new LayoutProcessor($scopeConfig, $phrase);

        $processResult = $layoutProcessor->process($this->jsLayout);
        $result = $processResult['components']['checkout']['children']['steps']
            ['children']['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children']['comment_field'];

        // prepare Phrase
        $description = $result['config']['tooltip']['description'];
        $result['config']['tooltip']['description'] = $description->getText();
        $label = $result['label'];
        $result['label'] = $label->getText();

        $this->assertSame($this->commentField, $result);
    }
}
