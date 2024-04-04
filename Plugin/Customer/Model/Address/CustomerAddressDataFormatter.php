<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Model\Address;

/**
 * 
 * Fix a problem when customer address extension attributes are erased 
 * during from server to storefront
 * 
 */
class CustomerAddressDataFormatter
{
    /**
     * @param \Magento\Customer\Model\Address\CustomerAddressDataFormatter $subject
     * @param array $result
     * @return array
     */
    public function afterPrepareAddress($subject, $result)
    {
        /** @var \Magento\Customer\Api\Data\AddressExtension $extensionAttributes */
        $extensionAttributes = $result['extension_attributes'];
        $extToArr = $extensionAttributes->__toArray();
        $result['extension_attributes'] = $extToArr;

        return $result;
    }
}
