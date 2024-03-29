<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Model;

use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentCustomerSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

class ShippingCommentCustomerSearchResults extends SearchResults implements ShippingCommentCustomerSearchResultsInterface
{
}
