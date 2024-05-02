<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Api\Data;

/**
 * Shipping Comment CRUD interface.
 *
 * @api
 * */
interface ShippingCommentInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const ENTITY_ID = 'entity_id';
    public const QUOTE_ADDRESS_ID = 'quote_address_id';
    public const COMMENT = 'comment';

    /**
     * Retrive comment id
     *
     * @return int
     */
    public function getId();

    /**
     * Retrive quote address id
     *
     * @return int
     */
    public function getQuoteAddressId(): int;

    /**
     * Retrive comment text
     *
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
    public function setQuoteAddressId(int $quoteAddressId): ShippingCommentInterface;

    /**
     * Update Comment
     *
     * @param string $comment
     * @return ShippingCommentInterface
     */
    public function setComment(string $comment): ShippingCommentInterface;
}
