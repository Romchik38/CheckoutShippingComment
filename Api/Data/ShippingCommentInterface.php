<?php

namespace Romchik38\CheckoutShippingComment\Api\Data;

/**
 * Shipping Comment interface.
 * @api
 * */
interface ShippingCommentInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID = 'entity_id';
    const QUOTE_ADDRESS_ID = 'quote_address_id';
    const COMMENT = 'comment';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getQuoteAddressId(): int;

    /**
     * @return string
     */
    public function getComment(): string;

    /**
     * Set ID
     *
     * @param int $id
     * @return ShippingCommentInterface
     */
    public function setId($id): ShippingCommentInterface;

    /**
     * Set quote_address_id
     *
     * @param int $quoteAddressId
     * @return ShippingCommentInterface
     */
    public function seQuoteAddressId(int $quoteAddressId): ShippingCommentInterface;

    /**
     * Update Comment
     *
     * @param string $comment
     * @return ShippingCommentInterface
     */
    public function setComment(string $comment): ShippingCommentInterface;
}
