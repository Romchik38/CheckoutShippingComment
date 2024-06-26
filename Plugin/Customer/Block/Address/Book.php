<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Block\Address;

use Romchik38\CheckoutShippingComment\Api\ShippingCommentCustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Escaper;

/**
 * Plugin add a comment to Default Addresses
 * area - storefront
 * url - customer_address_index
 */
class Book
{
    /**
     * @param ShippingCommentCustomerRepositoryInterface $shippingCommentCustomerRepository
     * @param \Magento\Framework\Escaper $escaper
     */
    public function __construct(
        private ShippingCommentCustomerRepositoryInterface $shippingCommentCustomerRepository,
        private Escaper $escaper
    ) {
    }

    /**
     * Add comment text
     *
     * @param \Magento\Customer\Block\Address\Book $subject
     * @param string $result
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
                $commentFieldEscaped = $this->escaper->escapeHtml($commentField);
                $result = $result . '<br><span class="comment">' . __('comment: ') . ($commentFieldEscaped) . '</span>';
            }
        } catch (NoSuchEntityException $e) {
            
        }
        return $result;
    }
}
