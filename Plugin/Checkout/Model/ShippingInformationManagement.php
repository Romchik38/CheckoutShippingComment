<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Checkout\Model;

use Magento\Quote\Api\CartRepositoryInterface;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentRepository;
use Magento\Framework\Exception\NoSuchEntityException;

class ShippingInformationManagement
{
    public function __construct(
        private CartRepositoryInterface $quoteRepository,
        private ShippingCommentRepository $shippingCommentRepository
    ) {
    }

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

        // 1. exit ( extension attribute wasn't set )
        if ($commentField === null) {
            return $result;
        }

        // 2. check if comment with shippingAddressId already present in comment table
        try {
            $comment = $this
                ->shippingCommentRepository
                ->getByQuoteAddressId((int)$shippingAddressId);
        } catch (NoSuchEntityException $e) {
            $comment = $this->shippingCommentRepository->create();
            $comment->setQuoteAddressId((int)$shippingAddressId);
        }
        $comment->setComment($commentField);
        $this->shippingCommentRepository->save($comment);

        return $result;
    }
}
