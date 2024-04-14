<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Model;

use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentCustomerInterface;
use Magento\Framework\Model\AbstractModel;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingCommentCustomer as ShippingCommentCustomerResource;

class ShippingCommentCustomer extends AbstractModel implements ShippingCommentCustomerInterface
{
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        private ShippingCommentCustomerResource $shippingCommentCustomerResource
    )
    {
        parent::__construct($context, $registry);
    }

    protected function _construct()

    {
        $this->_init($this->shippingCommentCustomerResource::class);
    }

    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    public function getCustomerAddressId(): int
    {
        return $this->getData(self::CUSTOMER_ADDRESS_ID);
    }

    public function getComment(): string
    {
        return $this->getData(self::COMMENT);
    }

    public function setId($id): ShippingCommentCustomerInterface
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    public function setCustomerAddressId(int $customerAddressId): ShippingCommentCustomerInterface
    {
        return $this->setData(self::CUSTOMER_ADDRESS_ID, $customerAddressId);
    }

    public function setComment(string $comment): ShippingCommentCustomerInterface
    {
        return $this->setData(self::COMMENT, $comment);
    }
}
