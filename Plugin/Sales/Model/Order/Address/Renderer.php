<?php
declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Sales\Model\Order\Address;

use Romchik38\CheckoutShippingComment\Model\ShippingCommentRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class Renderer {

    public function __construct(
        private ShippingCommentRepository $shippingCommentRepository,
        private SearchCriteriaBuilder $searchCriteriaBuilder,
        private FilterFactory $filterFactory
    )
    {}

    public function afterFormat(
        $subject,
        $result,
        $address, 
        $type
    ){
        if ($address->getAddressType() === 'shipping') {
            $quoteShippingAddressId = $address->getQuoteAddressId();

            try {
                $comment = $this
                    ->shippingCommentRepository
                    ->getByQuoteAddressId((int)$quoteShippingAddressId);
                    $result = $result . '<br><span style="shipping-comment">' . $comment->getComment() . '</span>';
            } catch(NoSuchEntityException $e) {}
        }
        return $result;
    }
}

