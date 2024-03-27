<?php
declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Model\Order\Address;

class Renderer {
    public function afterFormat(
        $subject,
        $result,
        ...$args
    ){

        return $result;
    }
}

