<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Block\Address;

use Romchik38\CheckoutShippingComment\Api\ShippingCommentCustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Plugin add comment to Default Addresses
 * area - storefront
 * url - customer_address_index 
 */
class Book
{

    public function __construct(
        private ShippingCommentCustomerRepositoryInterface $shippingCommentCustomerRepository
    ) {
    }

    /**
     * @param \Magento\Customer\Block\Address\Book $subject
     * @param \Magento\Customer\Api\Data\AddressInterface $address
     */
    public function afterGetAddressHtml(
        $subject,
        string $result,
        $address
    ) {
        $addressId = (int)$address->getId();
        try {
            $comment = $this->shippingCommentCustomerRepository->getByCustomerAddressId($addressId);
            $commentField = $comment->getComment();
            if ($commentField) {
                $result = $result . '<br><span class="comment">comment: ' . ($commentField) . '</span>';
            }
        } catch (NoSuchEntityException $e) {
        }
        return $result;
    }
}
