<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Checkout\Model;

use Magento\Quote\Api\CartRepositoryInterface;

class ShippingInformationManagement
{
    public function __construct(
        private CartRepositoryInterface $quoteRepository
    ) {
    }

    // public function beforeSaveAddressInformation(
    //     \Magento\Checkout\Model\ShippingInformationManagement $subject,
    //     $cartId,
    //     \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    // ) {
    //     /** @var \Magento\Quote\Model\Quote\Address $shippingAddress */
    //     $shippingAddress = $addressInformation->getShippingAddress();
    //     $extensionAttributes = $shippingAddress->getExtensionAttributes();

    //     /** @var \Magento\Quote\Model\Quote $quote */
    //     $quote = $this->quoteRepository->getActive($cartId);

    //     $commentField = $extensionAttributes->getCommentField();
    //     if ($commentField) {
    //         //do database request
    //     }

    //     return null;
    // }

    public function afterSaveAddressInformation(
        $subject,
        $result,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {

        /** @var \Magento\Quote\Model\Quote\Address $shippingAddress */
        $shippingAddress = $addressInformation->getShippingAddress();
        $extensionAttributes = $shippingAddress->getExtensionAttributes();
        

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        $quoteShippingAddress = $quote->getShippingAddress();
        $shippingAddressId = $quoteShippingAddress->getId();
        $saveInAddressBook = $quoteShippingAddress->getSaveInAddressBook();
        $customerAddressId = $quoteShippingAddress->getCustomerAddressId();

        $commentField = $extensionAttributes->getCommentField();
            
        //exit ( extension attribute wasn't set )
        if ($commentField === null) {
            return $result;
        }

        // 1. check if comment with shippingAddressId already present in comment table
        //  1.1 true - update comment
        //  1.2 false - insert new
        $a = 1;
        $b = $a + 1;
        // 2. check if $saveInAddressBook = 1
        //  2.1 true - save comment for customerAddressId

        return $result;
    }
}
