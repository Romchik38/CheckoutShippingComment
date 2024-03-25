<?php

namespace Romchik38\CheckoutShippingComment\Model;

use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentInterface;
use Magento\Framework\Model\AbstractModel;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment as ShippingCommentResource;

class ShippingComment extends AbstractModel implements ShippingCommentInterface
{
    protected function _construct()

    {
        $this->_init(ShippingCommentResource::class);
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

    public function seQuoteAddressId(int $quoteAddressId): ShippingCommentInterface
    {
        return $this->setData(self::QUOTE_ADDRESS_ID, $quoteAddressId);
    }

    public function setComment(string $comment): ShippingCommentInterface
    {
        return $this->setData(self::COMMENT, $comment);
    }
}
