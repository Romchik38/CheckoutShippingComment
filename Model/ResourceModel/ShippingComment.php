<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ShippingComment extends AbstractDb
{
    public const TABLE = 'rm38_checkout_shipping_comment';
    public const PRIMARY_FIELD = 'entity_id';

    /**
     * Init method
     */
    protected function _construct()
    {
        $this->_init(self::TABLE, self::PRIMARY_FIELD);
    }
}
