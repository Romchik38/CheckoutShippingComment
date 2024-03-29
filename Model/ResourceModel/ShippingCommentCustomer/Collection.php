<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingCommentCustomer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomer as ShippingCommentCustomerModel;
use Romchik38\CheckoutShippingComment\Model\ResourceModel\ShippingCommentCustomer as ShippingCommentCustomerResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init(
            ShippingCommentCustomerModel::class,
            ShippingCommentCustomerResource::class
        );
    }
}
