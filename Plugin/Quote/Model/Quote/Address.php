<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Plugin\Quote\Model\Quote;

class Address
{
  public function afterExportCustomerAddress(
    $subject,
    $result
  ){

    $extensionAttributes = $subject->getExtensionAttributes();
    $commentField = $extensionAttributes->getCommentField();
    if($commentField) {
        $resultExtensionAttributes = $result->getExtensionAttributes();
        $resultExtensionAttributes->setCommentField($commentField);
    }
    return $result;
  }
}
