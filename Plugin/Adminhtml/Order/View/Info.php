<?php
declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Adminhtml\Order\View;

class Info
{
    public function beforeGetOrder(
        $subject,
        ...$args
    ){
        $a = 1;
        $b = $a + 2;
    }

    public function afterGetOrder(
        $subject,
        $result,
        ...$args
    ){
        
        $orderId = $result->getId();
        $shippingAddress = $result->getShippingAddress();

        return $result;
    }

}
