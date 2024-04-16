<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Checkout\Model;

use Magento\Quote\Api\CartRepositoryInterface;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Api\Data\PaymentDetailsInterface;
use \Magento\Checkout\Model\ShippingInformationManagement as Subject;

/**
 * Saves a comment on shipping step for guests or customer with new address.
 * Customer ( guest ) push "Next" button and this plugin is activated
 * area - storefront
 * url - checkout/index/index
 */

class ShippingInformationManagement
{
    public function __construct(
        private CartRepositoryInterface $quoteRepository,
        private ShippingCommentRepository $shippingCommentRepository
    ) {
    }

    /**
     * @param Subject $subject
     * @param PaymentDetailsInterface $result
     * @param int $cartId
     * @return PaymentDetailsInterface
     * @throws NoSuchEntityException
     */
    public function afterSaveAddressInformation(
        $subject,
        $result,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {

        /** @var \Magento\Quote\Model\Quote\Address $shippingAddress */
        $shippingAddress = $addressInformation->getShippingAddress();
        $extensionAttributes = $shippingAddress->getExtensionAttributes();

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        $quoteShippingAddress = $quote->getShippingAddress();
        $shippingAddressId = $quoteShippingAddress->getId();

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
