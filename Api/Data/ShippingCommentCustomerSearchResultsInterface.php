<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ShippingCommentCustomerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Shipping Comment list
     *
     * @return ShippingCommentCustomerInterface[]
     */
    public function getItems();

    /**
     * Set Shipping Comment Customer list
     *
     * @param ShippingCommentCustomerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
