<?php
declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Model\Order\Address;

class Renderer {

    // public function beforeFormat(
    //     $subject,
    //     $address, 
    //     $type
    // ){
    //     if ($address->getAddressType() === 'shipping') {
    //         $addressType = $address->getAddressType();
    //         $extensionAttributes = $address->getExtensionAttributes();
            
    //         $extensionAttributes->setCommentField('some');
    //         $address->setExtensionAttributes($extensionAttributes);

    //         //$addressData = $address->getData();    
    //     }

    //     return [$address, $type];
    // }

    public function afterFormat(
        $subject,
        $result,
        $address, 
        $type
    ){
        if ($address->getAddressType() === 'shipping') {

        }

        return $result;
    }
}

