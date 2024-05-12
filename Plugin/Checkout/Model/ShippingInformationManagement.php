<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Checkout\Model;

use Magento\Quote\Api\CartRepositoryInterface;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Api\Data\PaymentDetailsInterface;
use \Magento\Checkout\Model\ShippingInformationManagement as Subject;
use Magento\Framework\Exception\CouldNotSaveException;
use \Psr\Log\LoggerInterface;

/**
 * Saves a comment on shipping step for guests or customer with new address.
 * Customer ( guest ) push "Next" button and this plugin is activated
 * area - storefront
 * url - checkout/index/index
 */

class ShippingInformationManagement
{
    /**
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Romchik38\CheckoutShippingComment\Model\ShippingCommentRepository $shippingCommentRepository
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        private CartRepositoryInterface $quoteRepository,
        private ShippingCommentRepository $shippingCommentRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * Saves provided comment to database
     *
     * @param Subject $subject
     * @param PaymentDetailsInterface $result
     * @param int $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
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

        $commentField = $extensionAttributes->getCommentField();

        // 1. exit ( extension attribute wasn't set )
        if ($commentField === null) {
            return $result;
        }

        // 2. check if comment with shippingAddressId already present in comment table
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        $quoteShippingAddress = $quote->getShippingAddress();
        $shippingAddressId = $quoteShippingAddress->getId();
        try {
            $comment = $this
                ->shippingCommentRepository
                ->getByQuoteAddressId((int)$shippingAddressId);
        } catch (NoSuchEntityException $e) {
            $comment = $this->shippingCommentRepository->create();
            $comment->setQuoteAddressId((int)$shippingAddressId);
        }
        $comment->setComment($commentField);
        try {
            $this->shippingCommentRepository->save($comment);
            // also change comment for quote
            $quoteShippingAddressExtensionAttributes = $quoteShippingAddress->getExtensionAttributes();
            $quoteShippingAddressExtensionAttributes->setCommentField($comment->getComment());
            $quoteShippingAddress->setExtensionAttributes($quoteShippingAddressExtensionAttributes);
        } catch (CouldNotSaveException $e) {
            $this->logger->critical(
                'Comment for shippingAddressId: '
                    . $shippingAddressId . ', with text: '
                    . $commentField .  ' was not save. Error: '
                    . $e->getMessage()
            );
        }

        return $result;
    }
}
