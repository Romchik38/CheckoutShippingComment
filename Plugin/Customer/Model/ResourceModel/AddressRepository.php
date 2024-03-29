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
        // ? check is it true
        \Magento\Customer\Api\Data\AddressInterface $result,
        $addressId,
    )
    {
        return $result;
    }

    public function afterGetList(
        $subject,
        \Magento\Customer\Api\Data\AddressSearchResultsInterface $searchResults
    )
    {

    }

    public function afterSave(
        $subject,
        // ? check is it true
        \Magento\Customer\Api\Data\AddressInterface $result,
        \Magento\Customer\Api\Data\AddressInterface $address,
    )
    {
        return $result;
    }

}
