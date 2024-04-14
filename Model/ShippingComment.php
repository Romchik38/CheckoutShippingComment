<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Model;

use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentInterface;
use Magento\Framework\Model\AbstractModel;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment as ShippingCommentResource;
use \Magento\Framework\Model\Context;
use \Magento\Framework\Registry;

class ShippingComment extends AbstractModel implements ShippingCommentInterface
{
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        private ShippingCommentResource $shippingCommentResource
    )
    {
        parent::__construct($context, $registry);
    }

    protected function _construct()

    {
        $this->_init($this->shippingCommentResource::class);
    }

    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    public function getQuoteAddressId(): int
    {
        return $this->getData(self::QUOTE_ADDRESS_ID);
    }

    public function getComment(): string
    {
        return $this->getData(self::COMMENT);
    }

    public function setId($id): ShippingCommentInterface
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    public function setQuoteAddressId(int $quoteAddressId): ShippingCommentInterface
    {
        return $this->setData(self::QUOTE_ADDRESS_ID, $quoteAddressId);
    }

    public function setComment(string $comment): ShippingCommentInterface
    {
        return $this->setData(self::COMMENT, $comment);
    }
}
