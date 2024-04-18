<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Controller\Address;

use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Romchik38\CheckoutShippingComment\Api\ShippingCommentCustomerRepositoryInterface;
use Psr\Log\LoggerInterface;
use \Magento\Customer\Model\Session;
use Magento\Customer\Api\AddressRepositoryInterface;
use \Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\Api\SearchCriteriaInterface;
use \Magento\Framework\Api\SearchCriteriaBuilder;
use \Magento\Framework\Api\SortOrder;
use \Magento\Framework\Api\SortOrderFactory;

/**
 * Save a customer comment
 * area - storefront
 * url - /customer/address/edit/id/1/
 */
class FormPost
{
    public function __construct(
        private ManagerInterface $messageManager,
        private RequestInterface $request,
        private ShippingCommentCustomerRepositoryInterface $shippingCommentCustomerRepository,
        private LoggerInterface $logger,
        private AddressRepositoryInterface $addressRepository,
        private Session $customerSession,
        private SearchCriteriaBuilder $searchCriteriaBuilder,
        private SortOrderFactory $sortOrderFactory
    ) {
    }

    /**
     * @param \Magento\Customer\Controller\Address\FormPost $subject
     * @param \Magento\Framework\Controller\Result\Redirect $result
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function afterExecute(
        $subject,
        $result
    ) {
        /** @var \Magento\Framework\Message\Collection $messages */
        $messages = $this->messageManager->getMessages();
        $items = $messages->getItems();
        $isSuccess = false;
        foreach ($items as $item) {
            $isSuccess = $item instanceof \Magento\Framework\Message\Success;
            if ($isSuccess) {
                break;
            }
        }
        if ($isSuccess === false) {
            return $result;
        }
        //Do job
        $addressIdParam = $this->request->getParam('id');
        $commentParam = $this->request->getParam('comment');
        // 1. client create a new address
        if (!$addressIdParam) {
            $this->saveNewAddress($commentParam);
            return $result;
        }
        // 2. client edit address
        // 2.1 do nothing because comment param wasn't provided
        if ($commentParam === null) {
            return $result;
        }
        // 2.2 Update existing comment
        try {
            $comment = $this->shippingCommentCustomerRepository->getByCustomerAddressId((int)$addressIdParam);
            $comment->setComment($commentParam);
            try {
                $this->shippingCommentCustomerRepository->save($comment);
            } catch (CouldNotSaveException $e) {
                $this->logger->critical('Error while updating shipping comment customer with customer address id: ' . $addressIdParam . ' ( request from customer/address/edit )');
            }
        } catch (NoSuchEntityException $e) {
        // 2.3 Create a new comment for existing address ( before module was enabled )
            $comment = $this->shippingCommentCustomerRepository->create();
            $comment->setComment($commentParam);
            $comment->setCustomerAddressId((int)$addressIdParam);
            try {
                $this->shippingCommentCustomerRepository->save($comment);
            } catch (CouldNotSaveException $e) {
                $this->logger->critical('Error while saving new shipping comment for existing customer with address id: ' . $addressIdParam . ' ( request from customer/address/edit )');
            }
        }

        return $result;
    }

    public function saveNewAddress($comment)
    {
        if (!$comment) {
            $commentField = '';
        } else {
            $commentField = $comment;
        }
        // 1. get a customer
        $customerId = $this->customerSession->getCustomerId();
        // 2. get last address
        /** @var SearchCriteriaInterface  $searchCriteria*/
        $sort = $this->sortOrderFactory->create()->setField('entity_id')
            ->setDirection(SortOrder::SORT_DESC);
        $this->searchCriteriaBuilder->setSortOrders([$sort]);
        $this->searchCriteriaBuilder->addFilter('parent_id', $customerId);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $addresses = $this->addressRepository->getList($searchCriteria)->getItems();
        $address = array_shift($addresses);
        if (!$address) {
            return;
        }
        // 3. chech if it dosn't have a comment
        $extensionAttributes = $address->getExtensionAttributes();
        $extensionAttributes->setCommentField($commentField);
        try {
            $this->addressRepository->save($address);
        } catch (LocalizedException $e) {
            $this->logger->critical('comment for customer address id ' . $address->getId() .  ' was not save');
        }
        return;
    }
}
