<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Model\Address;

class CustomerAddressDataFormatter
{
    public function afterPrepareAddress($subject, $result)
    {
        return $result;
    }
}
