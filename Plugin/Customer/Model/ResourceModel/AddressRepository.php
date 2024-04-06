<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Model\ResourceModel;

use Romchik38\CheckoutShippingComment\Api\ShippingCommentCustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * 
 * Save a comment for new or existing customer address
 * area - storefront 
 * url - checkout_index_index
 * 
 */
class AddressRepository
{

    public function __construct(
        private ShippingCommentCustomerRepositoryInterface $shippingCommentCustomerRepository,
        private ManagerInterface $messageManager,
        private LoggerInterface $logger
    ) {
    }

    /** 
     *  @param int $addressId 
     */
    public function afterGetById(
        $subject,
        \Magento\Customer\Model\Data\Address $result,
        $addressId,
    ) {
        $customerAddressId = $result->getId();
        $extensionAttributes = $result->getExtensionAttributes();

        $commentField = $extensionAttributes->getCommentField();

        if ($commentField) {
            return $result;
        }

        try {
            $comment = $this->shippingCommentCustomerRepository->getByCustomerAddressId((int)$customerAddressId);
            $extensionAttributes->setCommentField($comment->getComment());
            $result->setExtensionAttributes($extensionAttributes);
        } catch (NoSuchEntityException $e) {
        }
        return $result;
    }

    // public function afterGetList(){}
    // do not need, because inside getList() used getById():
    //      $addresses[] = $this->getById($address->getId());

    public function afterSave(
        $subject,
        \Magento\Customer\Api\Data\AddressInterface $result,
        \Magento\Customer\Api\Data\AddressInterface $address,
    ) {
        $customerAddressId = (int)$result->getId();
        $extensionAttributes = $address->getExtensionAttributes();
        $commentField = $extensionAttributes->getCommentField();
        // 1. exit if comment wasn't provided
        if(!$commentField) {
            return $result;
        }
        // 2. get comment
        try {
            $comment = $this->shippingCommentCustomerRepository->getByCustomerAddressId($customerAddressId);

            // 3. save comment for existing address
            try {
                $comment->setComment($commentField);
                $this->shippingCommentCustomerRepository->save($comment);
            } catch(CouldNotSaveException $e) {
                $this->messageManager->addErrorMessage(__("Error while updating address comment"));
                $this->logger->critical('Error while updating shipping comment customer with customerAddressId: ' . $customerAddressId);
            } 
        // 4. create new comment for new sddress
        } catch(NoSuchEntityException $e) {
            $comment = $this->shippingCommentCustomerRepository->create();
            $comment->setComment($commentField);
            $comment->setCustomerAddressId($customerAddressId);
            try {
                $this->shippingCommentCustomerRepository->save($comment);
            } catch(CouldNotSaveException $e) {
                $this->messageManager->addErrorMessage(__("Error while saving new address comment"));
                $this->logger->critical('Error while saving new shipping comment customer with customerAddressId: ' . $customerAddressId);
            }
        }
        return $result;
    }

    // public function afterDelete() {}
    // do not need, because there is a on delete cascade option in mysql
    // row is already deleted
}
