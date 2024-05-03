<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Model\Address;

/**
 * Fixes a problem when customer address extension attributes are erased
 * during transfer from server to storefront
 * ---
 * The data is serialized in Magento\Framework\Serialize\Serializer\JsonHexTag
 *  function serialize($data)
 *      $result = json_encode($data, JSON_HEX_TAG);
 * ---
 * after this point the extension attributes become empty
 *  this is because inside $data extension attributes are instance of
 *  Magento\Customer\Api\Data\AddressExtension
 * ---
 * The plugin convert them to array and then json_encode() will work as expected
 */

class CustomerAddressDataFormatter
{
    /**
     * Replace extension_attributes class with array
     *
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
        if (isset($extensionAttributes)
            && ($extensionAttributes instanceof \Magento\Customer\Api\Data\AddressExtension)
        ) {
            $extToArr = $extensionAttributes->__toArray();
            $result['extension_attributes'] = $extToArr;
        }

        return $result;
    }
}
