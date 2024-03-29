<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ShippingComment extends AbstractDb
{
    const TABLE = 'rm38_checkout_shipping_comment';
    const PRIMARY_FIELD = 'entity_id';

    protected function _construct()
    {
        $this->_init(self::TABLE, self::PRIMARY_FIELD);
    }
}
