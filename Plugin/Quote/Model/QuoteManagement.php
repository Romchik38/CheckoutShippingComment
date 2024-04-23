<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Quote\Model;

use Romchik38\CheckoutShippingComment\Api\ShippingCommentRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Add a shipping comment to quote extension attributes
 *  The Plugin starts after a customer pushed "Place order" button
 * area - storefront
 * url - checkout/index/index
 */
class QuoteManagement
{
    public function __construct(
        private ShippingCommentRepositoryInterface $shippingCommentRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @param \Magento\Quote\Model\QuoteManagement $subject
     * @param \Magento\Quote\Model\Quote $quote
     */
    public function beforeSubmit(
        $subject,
        $quote
    ): array|null {

        $quoteId = $quote->getId();

        $quoteShippingAddress = $quote->getShippingAddress();
        $saveInAddressBook = $quoteShippingAddress->getSaveInAddressBook();
        if ($saveInAddressBook === 0) {
            return null;
        }
        $shippingAddressId = $quoteShippingAddress->getId();

        try {
            $comment = $this->shippingCommentRepository
                ->getByQuoteAddressId((int)$shippingAddressId);
            $quoteShippingAddressExtensionAttributes = $quoteShippingAddress->getExtensionAttributes();
            $quoteShippingAddressExtensionAttributes->setCommentField($comment->getComment());
            $quoteShippingAddress->setExtensionAttributes($quoteShippingAddressExtensionAttributes);
        } catch (NoSuchEntityException $e) {
            $this->logger->critical('Error while getting shipping comment with shippingAddressId: ' . $shippingAddressId . ' while placing an order with quote_id: ' . $quoteId);
        }

        return [$quote];
    }
}
