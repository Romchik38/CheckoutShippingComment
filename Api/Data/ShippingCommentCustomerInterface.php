<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Api\Data;

/**
 * Shipping Comment Customer CRUD interface.
 *
 * @api
 * */
interface ShippingCommentCustomerInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const ENTITY_ID = 'entity_id';
    public const CUSTOMER_ADDRESS_ID = 'customer_address_id';
    public const COMMENT = 'comment';

    /**
     * Get comment id
     *
     * @return int
     */
    public function getId();

    /**
     * Get customer address id
     *
     * @return int
     */
    public function getCustomerAddressId(): int;

    /**
     * Get comment text
     *
     * @return string
     */
    public function getComment(): string;

    /**
     * Set ID
     *
     * @param int $id
     * @return ShippingCommentCustomerInterface
     */
    public function setId($id): ShippingCommentCustomerInterface;

    /**
     * Set customer_address_id
     *
     * @param int $customerAddressId
     * @return ShippingCommentCustomerInterface
     */
    public function setCustomerAddressId(int $customerAddressId): ShippingCommentCustomerInterface;

    /**
     * Update Comment
     *
     * @param string $comment
     * @return ShippingCommentCustomerInterface
     */
    public function setComment(string $comment): ShippingCommentCustomerInterface;
}
