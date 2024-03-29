<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Model\ResourceModel;

class AddressRepository
{

    /** 
     *  @param int $addressId 
     */
    public function afterGetById(
        $subject,
        \Magento\Customer\Model\Data\Address $result,
        $addressId,
    ) {
        $a = 1;
        $b = 1 + $a;
        return $result;
    }

    public function afterGetList(
        $subject,
        // ? check is it true
        \Magento\Customer\Api\Data\AddressSearchResultsInterface $searchResults
    ) {
        return $searchResults;
    }

    public function afterSave(
        $subject,
        // ? check is it true
        \Magento\Customer\Api\Data\AddressInterface $result,
        \Magento\Customer\Api\Data\AddressInterface $address,
    ) {
        return $result;
    }

    public function afterDelete(
        $subject,
        // ? check is it true
        bool $result,
        \Magento\Customer\Api\Data\AddressInterface $address
    ) {
        return $result;
    }
}
