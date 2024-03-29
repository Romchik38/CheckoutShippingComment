<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Model\ResourceModel;

use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerRepository;
use Magento\Framework\Exception\NoSuchEntityException;

class AddressRepository
{

    public function __construct(
        private ShippingCommentCustomerRepository $shippingCommentCustomerRepository
    )
    {
        
    }

    /** 
     *  @param int $addressId 
     */
    public function afterGetById(
        $subject,
        \Magento\Customer\Model\Data\Address $result,
        $addressId,
    ) {
        $customerAddressId = $result->getId();
        $extensionAttributes = $result->getExtensionAttributes();

        try {
            $comment = $this->shippingCommentCustomerRepository->getByCustomerAddressId((int)$customerAddressId);
            $extensionAttributes->setCommentField($comment->getComment());
            $result->setExtensionAttributes($extensionAttributes);
        } catch(NoSuchEntityException $e) {
        }
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
