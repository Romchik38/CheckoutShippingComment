<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Model;

use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentInterface;
use Magento\Framework\Model\AbstractModel;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment as ShippingCommentResource;

class ShippingComment extends AbstractModel implements ShippingCommentInterface
{
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment $shippingCommentResource
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        private ShippingCommentResource $shippingCommentResource
    ) {
        parent::__construct($context, $registry);
    }

    /**
     * Initialize method
     */
    protected function _construct()
    {
        $this->_init($this->shippingCommentResource::class);
    }

    /**
     * Retrive comment id
     */
    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Retrive Quote Address Id
     *
     * @return int
     */
    public function getQuoteAddressId(): int
    {
        return $this->getData(self::QUOTE_ADDRESS_ID);
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
    public function setId($id): ShippingCommentInterface
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Set Quote Address Id
     *
     * @param int $quoteAddressId
     * @return ShippingCommentInterface
     */
    public function setQuoteAddressId(int $quoteAddressId): ShippingCommentInterface
    {
        return $this->setData(self::QUOTE_ADDRESS_ID, $quoteAddressId);
    }

    /**
     * Set comment text
     *
     * @param string $comment
     * @return ShippingCommentInterface
     */
    public function setComment(string $comment): ShippingCommentInterface
    {
        return $this->setData(self::COMMENT, $comment);
    }
}
