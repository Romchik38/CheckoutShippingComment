<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ShippingCommentSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Shipping Comment list
     *
     * @return ShippingCommentInterface[]
     */
    public function getItems();

    /**
     * Set Shipping Comment list
     *
     * @param ShippingCommentInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
