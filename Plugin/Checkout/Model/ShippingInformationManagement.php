<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Checkout\Model;

use Magento\Quote\Api\CartRepositoryInterface;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterFactory;

class ShippingInformationManagement
{
    public function __construct(
        private CartRepositoryInterface $quoteRepository,
        private ShippingCommentRepository $shippingCommentRepository,
        private SearchCriteriaBuilder   $searchCriteriaBuilder,
        private FilterFactory           $filterFactory
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
            
        //exit ( extension attribute wasn't set )
        if ($commentField === null) {
            return $result;
        }

        // 1. check if comment with shippingAddressId already present in comment table
        //  1.1 true - update comment
        //  1.2 false - insert new
        
        // 2. check if $saveInAddressBook = 1
        //  2.1 true - save comment for customerAddressId

        // $filter = $this->filterFactory->create();
        // $filter->setField('quote_address_id')->setValue($shippingAddressId)->setConditionType('eq');
        // $this->searchCriteriaBuilder->addFilters([$filter]);
        // $searchCriteria = $this->searchCriteriaBuilder->create();
        // $comments = $this->shippingCommentRepository->getList($searchCriteria)->getItems();
        // if (count($comments) === 0) {
        //     $comment = $this->shippingCommentRepository->create();
        //     $comment->seQuoteAddressId((int)$shippingAddressId);
        // } else {
        //     $comment = array_shift($comments);
        // }
        // $comment->setComment($commentField);
        // $this->shippingCommentRepository->save($comment);

        return $result;
    }
}
