<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Model;

use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentCustomerInterface;
use Magento\Framework\Model\AbstractModel;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingCommentCustomer as ShippingCommentCustomerResource;

class ShippingCommentCustomer extends AbstractModel implements ShippingCommentCustomerInterface
{
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ShippingCommentCustomer $shippingCommentCustomerResource
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        private ShippingCommentCustomerResource $shippingCommentCustomerResource
    ) {
        parent::__construct($context, $registry);
    }

    /**
     * Initialize method
     */
    protected function _construct()
    {
        $this->_init($this->shippingCommentCustomerResource::class);
    }

    /**
     * Retrive comment id
     */
    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Retrive Customer Address Id
     *
     * @return int
     */
    public function getCustomerAddressId(): int
    {
        return $this->getData(self::CUSTOMER_ADDRESS_ID);
    }

    /**
     * Retrive comment text
     *
     * @return string
     */
    public function getComment(): string
    {
        return $this->getData(self::COMMENT);
    }

    /**
     * Set comment id
     *
     * @param int $id
     */
    public function setId($id): ShippingCommentCustomerInterface
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Set Customer Address Id
     *
     * @param int $customerAddressId
     * @return ShippingCommentCustomerInterface
     */
    public function setCustomerAddressId(int $customerAddressId): ShippingCommentCustomerInterface
    {
        return $this->setData(self::CUSTOMER_ADDRESS_ID, $customerAddressId);
    }

    /**
     * Set comment text
     *
     * @param string $comment
     * @return ShippingCommentCustomerInterface
     */
    public function setComment(string $comment): ShippingCommentCustomerInterface
    {
        return $this->setData(self::COMMENT, $comment);
    }
}
