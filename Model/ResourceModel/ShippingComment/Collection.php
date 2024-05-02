<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Romchik38\CheckoutShippingComment\Model\ShippingComment as ShippingCommentModel;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingComment as ShippingCommentResource;

class Collection extends AbstractCollection
{
    /**
     * Init method
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(
            ShippingCommentModel::class,
            ShippingCommentResource::class
        );
    }
}
