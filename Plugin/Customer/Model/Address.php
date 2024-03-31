<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Model;

use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerRepository;
use Magento\Framework\Exception\NoSuchEntityException;

class Address
{

    public function __construct(
        private ShippingCommentCustomerRepository $shippingCommentCustomerRepository
    ) {
    }

    public function afterGetDataModel(
        $subject,
        \Magento\Customer\Model\Data\Address $result
    ) {

        $customerAddressId = $result->getId();
        $extensionAttributes = $result->getExtensionAttributes();

        try {
            $comment = $this->shippingCommentCustomerRepository->getByCustomerAddressId((int)$customerAddressId);
            $extensionAttributes->setCommentField($comment->getComment());
            $result->setExtensionAttributes($extensionAttributes);
        } catch (NoSuchEntityException $e) {
        }

        return $result;
    }
}
