<?php
declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Model;

use Romchik38\CheckoutShippingComment\Api\Data\ShippingCommentSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

class ShippingCommentSearchResults extends SearchResults implements ShippingCommentSearchResultsInterface
{
}

