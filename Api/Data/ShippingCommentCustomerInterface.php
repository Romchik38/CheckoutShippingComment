<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Api\Data;

/**
 * Shipping Comment Customer interface.
 * @api
 * */
interface ShippingCommentCustomerInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID = 'entity_id';
    const CUSTOMER_ADDRESS_ID = 'customer_address_id';
    const COMMENT = 'comment';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getCustomerAddressId(): int;

    /**
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
