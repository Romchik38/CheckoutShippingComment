<?php
declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Sales\Model\Order\Address;

use Romchik38\CheckoutShippingComment\Model\ShippingCommentRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterFactory;

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


            $filter = $this->filterFactory->create();
            $filter
                ->setField('quote_address_id')
                ->setValue($quoteShippingAddressId)
                ->setConditionType('eq');
            $this->searchCriteriaBuilder->addFilters([$filter]);
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $comments = $this->shippingCommentRepository->getList($searchCriteria)->getItems();
            if (count($comments) === 1) {
                $comment = array_shift($comments);
                $result = $result . '<br><span style="shipping-comment">' . $comment->getComment() . '</span>';
            }
        }
        return $result;
    }
}

