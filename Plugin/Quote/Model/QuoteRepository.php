<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Quote\Model;

use Romchik38\CheckoutShippingComment\Api\ShippingCommentRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class QuoteRepository
{
    /**
     * @param ShippingCommentRepositoryInterface $shippingCommentRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        private ShippingCommentRepositoryInterface $shippingCommentRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Add commentField to Quote Extension Attributes
     *
     * @param \Magento\Quote\Model\QuoteRepository $subject
     * @param \Magento\Quote\Model\Quote $result
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    public function afterGetActive($subject, $result)
    {
        $quoteShippingAddress = $result->getShippingAddress();
        $shippingAddressId = $quoteShippingAddress->getId();

        $quoteShippingAddressExtensionAttributes = $quoteShippingAddress->getExtensionAttributes();
        $quoteCommentField = $quoteShippingAddressExtensionAttributes->getCommentField();

        // 1. Comment already was set
        if ($quoteCommentField !== null) {
            return $result;
        }

        // 2. Add a comment to quote
        try {
            $comment = $this->shippingCommentRepository
                ->getByQuoteAddressId((int)$shippingAddressId);
            $quoteShippingAddressExtensionAttributes = $quoteShippingAddress->getExtensionAttributes();
            $quoteShippingAddressExtensionAttributes->setCommentField($comment->getComment());
            $quoteShippingAddress->setExtensionAttributes($quoteShippingAddressExtensionAttributes);
        } catch (NoSuchEntityException $e) {
            // Do nothing, because comment migth not be set
        }

        return $result;
    }
}
