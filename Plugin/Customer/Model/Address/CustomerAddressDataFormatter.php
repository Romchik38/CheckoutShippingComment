<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Customer\Model\Address;

class CustomerAddressDataFormatter
{
    public function afterPrepareAddress($subject, $result)
    {
        $extensionAttributes = $result['extension_attributes'];

        //Temporary solution while Comment extension attribute 
        //for Customer Address doesn't work
        $extensionAttributes->setCommentField('comment 1');
        //End temporary solution

        $extToArr = $extensionAttributes->__toArray();
        $result['extension_attributes'] = $extToArr;
        return $result;
    }
}
