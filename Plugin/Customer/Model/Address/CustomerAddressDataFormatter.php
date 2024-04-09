<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Model\Address;

/**
 * 
 * Fixes a problem when customer address extension attributes are erased 
 * during transfer from server to storefront
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

        if (array_key_exists('extension_attributes', $result) === false) {
            return $result;
        }

        $extensionAttributes = $result['extension_attributes'];
        if (isset($extensionAttributes)) {
            $extToArr = $extensionAttributes->__toArray();
            $result['extension_attributes'] = $extToArr;
        }

        return $result;
    }
}
