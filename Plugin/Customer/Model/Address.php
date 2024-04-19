<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Model;

use Romchik38\CheckoutShippingComment\Api\ShippingCommentCustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * 
 * Retrive a comment customer and set it to customer address extension attributes 
 * area - storefront
 * page - chechout_index_index
 * as a result, the comment will be shown with other address fields in the address list
 */

class Address
{

    public function __construct(
        private ShippingCommentCustomerRepositoryInterface $shippingCommentCustomerRepository
    ) {
    }

    /**
     * @param \Magento\Customer\Model\Address $subject
     */
    public function afterGetDataModel(
        $subject,
        \Magento\Customer\Model\Data\Address $result
    ) {

        $customerAddressId = $result->getId();
        $extensionAttributes = $result->getExtensionAttributes();

        try {
            $comment = $this->shippingCommentCustomerRepository
                ->getByCustomerAddressId((int)$customerAddressId);
            $extensionAttributes->setCommentField($comment->getComment());
            $result->setExtensionAttributes($extensionAttributes);
        } catch (NoSuchEntityException $e) {
            // do nothing because the client address 
            // may be created before the module is enabled
        }

        return $result;
    }
}
