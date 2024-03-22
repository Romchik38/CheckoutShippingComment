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

        $commentField = $extensionAttributes->getCommentField();
        //save comment only for quests and customer new address with no save in address book
        if ($commentField && $shippingAddressId && ($saveInAddressBook === '0')) {
            //do database request
            $doRequest = true;
        }

        return $result;
    }
}
