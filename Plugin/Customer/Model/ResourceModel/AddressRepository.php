<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Model\ResourceModel;

use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;

class AddressRepository
{

    public function __construct(
        private ShippingCommentCustomerRepository $shippingCommentCustomerRepository,
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
        $customerAddressId = $result->getId();
        $extensionAttributes = $address->getExtensionAttributes();
        $commentField = $extensionAttributes->getCommentField();
        // 1. exit if comment wasn't provided
        if(!$commentField) {
            return $result;
        }
        // 2. get comment
        try {
            $comment = $this->shippingCommentCustomerRepository->getByCustomerAddressId($customerAddressId);
        // 3. save comment
            try {
                $comment->setComment($commentField);
                $this->shippingCommentCustomerRepository->save($comment);
            } catch(CouldNotSaveException $e) {
                $this->messageManager->addErrorMessage(__("Error while saving address comment"));
                $this->logger->critical('Error while saving shipping comment customer with id: ' . $customerAddressId);
            }
        } catch(NoSuchEntityException $e) {
        }
        return $result;
    }

    // public function afterDelete() {}
    // do not need, because there is a on delete cascade option in mysql
    // row alraedy deleted
}
