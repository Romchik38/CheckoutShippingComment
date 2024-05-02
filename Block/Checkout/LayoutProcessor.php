<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class LayoutProcessor implements LayoutProcessorInterface
{

    protected const CONFIG_SORTORDER_PATH = 'checkoutshippingcomment/checkout/comment_sortOrder';

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
    ) {
    }

    /**
     * Add a comment field to the jsLayout
     *
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {

        $sortOrder = (int)$this->scopeConfig->getValue($this::CONFIG_SORTORDER_PATH);
        $commentAttributeCode = 'comment_field';

        $commentField = [

            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/textarea',
                'cols' => '20',
                'rows' => '2',
                'tooltip' => [
                    'description' => __('add additional information to delivery'),
                ]
            ],
            'dataScope' => 'shippingAddress.custom_attributes' . '.' . $commentAttributeCode,
            'label' => __('Shipping Comment'),
            'provider' => 'checkoutProvider',
            'sortOrder' => $sortOrder,
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

        $jsLayout['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children']
            [$commentAttributeCode] = $commentField;

        return $jsLayout;
    }
}
