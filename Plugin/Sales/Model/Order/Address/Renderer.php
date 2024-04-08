<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Sales\Model\Order\Address;

use Romchik38\CheckoutShippingComment\Api\ShippingCommentRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Escaper;

/**
 * The plugin add a comment to the Shipping Address in Order view
 * area - Admin->Sales->Orders ( click view on concrete order )
 */
class Renderer
{

    public function __construct(
        private ShippingCommentRepositoryInterface $shippingCommentRepository,
        private SearchCriteriaBuilder $searchCriteriaBuilder,
        private FilterFactory $filterFactory,
        private Escaper $escaper
    ) {
    }

    /**
     * @param \Magento\Sales\Model\Order\Address\Renderer $subject
     * @param string|null $result
     */
    public function afterFormat(
        $subject,
        $result,
        \Magento\Sales\Model\Order\Address $address
    ) {
        if ($result === null) {
            return $result;
        }
        if ($address->getAddressType() === 'shipping') {
            $quoteShippingAddressId = (int)$address->getQuoteAddressId();

            try {
                $comment = $this
                    ->shippingCommentRepository
                    ->getByQuoteAddressId($quoteShippingAddressId);
                $result = $result . '<br><span style="shipping-comment">' . $this->escaper->escapeHtml($comment->getComment()) . '</span>';
            } catch (NoSuchEntityException $e) {
            }
        }
        return $result;
    }
}
